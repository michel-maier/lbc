<?php

namespace App\Core\Ads\Application;

interface GetAdServiceInterface
{
    public function __invoke(string $uuid): DefaultAdResponse;
}