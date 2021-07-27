<?php

namespace App\Core\Ads\Infrastructure;

use App\Core\Ads\Domain\Ad;

interface AdRepositoryInterface
{
    public function add(Ad $ad): Ad;
    public function save(Ad $ad): Ad;
    public function remove(Ad $ad): void;
    /* @return Ad[]*/
    public function findAll(): array;
}