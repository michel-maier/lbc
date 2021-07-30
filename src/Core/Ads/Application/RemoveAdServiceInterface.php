<?php

namespace App\Core\Ads\Application;

interface RemoveAdServiceInterface
{
    public function __invoke(string $uuid): RemoveAdResponse;
}
