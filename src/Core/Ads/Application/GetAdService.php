<?php

namespace App\Core\Ads\Application;

use App\Core\Ads\Domain\AdId;
use App\Core\Ads\Infrastructure\AdRepositoryInterface;

class GetAdService implements GetAdServiceInterface
{
    private AdRepositoryInterface $adRepository;

    public function __construct(AdRepositoryInterface $adRepository)
    {
        $this->adRepository = $adRepository;
    }

    public function __invoke(string $uuid): DefaultAdResponse
    {
        return (new AdDtoMapper())->toDefault($this->adRepository->get(new AdId($uuid)));
    }
}
