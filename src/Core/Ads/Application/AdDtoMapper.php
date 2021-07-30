<?php

namespace App\Core\Ads\Application;

use App\Core\Ads\Domain\Ad;
use App\Core\Ads\Domain\AutomobileAd;

class AdDtoMapper
{
    public function toDefault(Ad $ad): DefaultAdResponse
    {
        return new DefaultAdResponse(
            $ad->getId(),
            $ad->getTitle(),
            $ad->getContent(),
            $ad->getType(),
            $ad instanceof AutomobileAd ? $ad->getModel() : null,
            $ad instanceof AutomobileAd ? $ad->getManufacturer() : null
        );
    }

    public function toListed(Ad $ad): AdListResponse
    {
        return new AdListResponse(
            $ad->getId(),
            $ad->getTitle(),
            $ad->getContent(),
            $ad->getType()
        );
    }

    public function toCollection(array $collection): array
    {
        $dtos = [];
        while ($ad = array_pop($collection)) {
            $dtos[] = $this->toListed($ad);
        }

        return $dtos;
    }
}
