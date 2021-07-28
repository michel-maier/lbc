<?php

namespace App\Core\Ads\Application;

interface UpdateAdServiceInterface
{
    public function __invoke(UpdateAdRequest $request): DefaultAdResponse;
}