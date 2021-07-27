<?php

namespace App\Core\Ads\Domain;

class JobAd extends Ad
{
    public function getType(): string
    {
        return self::JOB_TYPE;
    }
}