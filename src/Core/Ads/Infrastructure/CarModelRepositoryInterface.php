<?php

namespace App\Core\Ads\Infrastructure;

use App\Core\Ads\Domain\CarModel;

interface CarModelRepositoryInterface
{
    /* @return CarModel[]*/
    public function findAll(): array;
}
