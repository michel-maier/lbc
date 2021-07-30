<?php

namespace App\Core\Ads\Domain;

class RealEstateAd extends Ad
{
    public function getType(): string
    {
        return self::REAL_ESTATE_TYPE;
    }
}
