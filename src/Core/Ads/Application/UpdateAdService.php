<?php

namespace App\Core\Ads\Application;

use App\Core\Ads\Domain\AdId;
use App\Core\Ads\Domain\AutomobileAd;
use App\Core\Ads\Infrastructure\AdRepositoryInterface;
use App\Core\Ads\Infrastructure\CarModelRepositoryInterface;

class UpdateAdService implements UpdateAdServiceInterface
{
    private AdRepositoryInterface $adRepository;
    private CarModelRepositoryInterface $carModelRepository;

    public function __construct(AdRepositoryInterface $adRepository, CarModelRepositoryInterface $carModelRepository)
    {
        $this->adRepository = $adRepository;
        $this->carModelRepository = $carModelRepository;
    }

    public function __invoke(UpdateAdRequest $request): UpdateAdResponse
    {
        $ad = $this->adRepository->get(new AdId($request->getId()));
        $ad = $this->adRepository->save($ad->updateFromUpdateRequest($request, $this->carModelRepository));

        return new UpdateAdResponse(
            $ad->getId(),
            $ad->getTitle(),
            $ad->getContent(),
            $ad->getType(),
            $ad instanceof AutomobileAd ? $ad->getModel() : null,
            $ad instanceof AutomobileAd ? $ad->getManufacturer() : null
        );
    }
}