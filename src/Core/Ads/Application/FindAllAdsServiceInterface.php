<?php

namespace App\Core\Ads\Application;

interface FindAllAdsServiceInterface
{
    /**
     * @return DefaultAdResponse[]
     */
    public function __invoke(): array;
}