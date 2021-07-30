<?php

namespace App\Core\Ads\Domain;

use App\Core\Ads\Application\NewAdRequest;
use App\Core\Ads\Application\UpdateAdRequest;
use App\Core\Ads\Infrastructure\CarModelRepositoryInterface;
use App\Core\DomainException;

abstract class Ad
{
    const JOB_TYPE = 'job';
    const REAL_ESTATE_TYPE = 'realEstate';
    const AUTOMOBILE_TYPE = 'automobile';
    const ALL_TYPES = [self::JOB_TYPE, self::REAL_ESTATE_TYPE, self::AUTOMOBILE_TYPE];

    const MAX_POSITION_RESEARCH = 99;

    private AdId $id;
    private string $title;
    private string $content;

    public function __construct(string $title, string $content)
    {
        $this->id = new AdId();
        $this->title = $title;
        $this->content = $content;
    }

    public static function createFromNewRequest(NewAdRequest $request, CarModelRepositoryInterface $carModelRepository): self
    {
        self::validateNewRequest($request);

        if (self::AUTOMOBILE_TYPE === $request->getType()) {
            return self::createFromNewAutomobileRequest($request, $carModelRepository);
        }

        return new (sprintf('%s\%sAd', __NAMESPACE__, ucfirst($request->getType())))($request->getTitle(), $request->getContent());
    }

    public static function createFromNewAutomobileRequest(NewAdRequest $request, CarModelRepositoryInterface $carModelRepository): self
    {
        $model = self::searchModel($request->getModel(), $carModelRepository);

        return new AutomobileAd($request->getTitle(), $request->getContent(), $model->getName(), $model->getManufacturer());
    }

    private static function validateNewRequest(NewAdRequest $request): void
    {
        $isAnAuthorizedType = in_array($request->getType(), self::ALL_TYPES);
        $isAnAuthorizedType || throw new DomainException('"%s" type is not authorized');
    }

    private static function searchModel(string $search, CarModelRepositoryInterface $carModelRepository): CarModel
    {
        $found = null;
        $w = CarModel::MAX_POSITION_RESEARCH;
        $l = 0;
        foreach($carModelRepository->findAll() as $model)
        {
            [$cw, $cl] = $model->matchingTheSearchString($search);
            $isFound = $cw !== CarModel::MAX_POSITION_RESEARCH;
            $isMoreAtLeft = $cw < $w;
            $hasSameLength = $cw === $w;
            $modelNameIsLargest =  $cl > $l;

            (($isFound && $isMoreAtLeft) || ($hasSameLength && $modelNameIsLargest)) && ($found = $model) && ($w = $cw);
        }

        (CarModel::MAX_POSITION_RESEARCH === $w) && throw new DomainException(sprintf('"%s" does not match any model', $search));

        return $found;
    }

    private static function sortCarModelsByLengthName($models): array
    {
        usort($models, function (CarModel $m1, CarModel $m2) {
            return strcmp($m1->getName(), $m2->getName());
        });

        return $models;
    }

    public function updateFromUpdateRequest(UpdateAdRequest $request, CarModelRepositoryInterface $carModelRepository): self
    {
        $this->title = $request->getTitle() ?? $this->title;
        $this->content = $request->getContent() ?? $this->content;

        if (self::AUTOMOBILE_TYPE === $this->getType() && null !== $request->getModel()) {
            return $this->updateFromNewAutomobileRequest($request, $carModelRepository);
        }

        return $this;
    }

    public function updateFromNewAutomobileRequest(UpdateAdRequest $request, CarModelRepositoryInterface $carModelRepository): self
    {
        $model = $this->searchModel($request->getModel(), $carModelRepository);

        return new AutomobileAd($request->getTitle(), $request->getContent(), $model->getName(), $model->getManufacturer());
    }

    public function getId(): AdId
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    abstract public function getType();
}