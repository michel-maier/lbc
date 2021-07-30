<?php

namespace App\Core\Ads\Application;

use App\Core\Ads\Domain\Ad;
use App\Core\Ads\Infrastructure\AdRepositoryInterface;
use App\Core\Ads\Infrastructure\CarModelRepositoryInterface;

class NewAdService implements NewAdServiceInterface
{
    private AdRepositoryInterface $adRepository;
    private CarModelRepositoryInterface $carModelRepository;

    public function __construct(AdRepositoryInterface $adRepository, CarModelRepositoryInterface $carModelRepository)
    {
        $this->adRepository = $adRepository;
        $this->carModelRepository = $carModelRepository;
    }

    public function __invoke(NewAdRequest $request): DefaultAdResponse
    {
        $ad = $this->adRepository->add(Ad::createFromNewRequest($request, $this->carModelRepository));

        return (new AdDtoMapper())->toDefault($ad);
    }
}
