<?php

namespace App\Core\Ads\Application;

interface NewAdServiceInterface
{
    public function __invoke(NewAdRequest $request): DefaultAdResponse;
}
