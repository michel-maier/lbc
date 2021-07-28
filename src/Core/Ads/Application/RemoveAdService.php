<?php

namespace App\Core\Ads\Application;

use App\Core\Ads\Domain\AdId;
use App\Core\Ads\Infrastructure\AdRepositoryInterface;

class RemoveAdService implements RemoveAdServiceInterface
{
    private AdRepositoryInterface $adRepository;

    public function __construct(AdRepositoryInterface $adRepository)
    {
        $this->adRepository = $adRepository;
    }

    public function __invoke(string $uuid): RemoveAdResponse
    {
        $this->adRepository->remove(new AdId($uuid));

        return new RemoveAdResponse($uuid);
    }
}