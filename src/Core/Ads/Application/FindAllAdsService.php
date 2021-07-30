<?php

namespace App\Core\Ads\Application;

use App\Core\Ads\Infrastructure\AdRepositoryInterface;

class FindAllAdsService implements FindAllAdsServiceInterface
{
    private AdRepositoryInterface $adRepository;

    public function __construct(AdRepositoryInterface $adRepository)
    {
        $this->adRepository = $adRepository;
    }

    public function __invoke(): array
    {
        return (new AdDtoMapper())->toDefaultCollection($this->adRepository->findAll());
    }
}
