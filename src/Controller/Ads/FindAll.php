<?php

namespace App\Controller\Ads;

use App\Controller\JsonApiRequestCoreAdapter;
use App\Core\Ads\Application\AdListResponse;
use App\Core\Ads\Application\DefaultAdResponse;
use App\Core\Ads\Application\FindAllAdsServiceInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/ads', name: 'ads_find_all', methods: ['GET'])]
class FindAll
{
    public function __invoke(Request $request, FindAllAdsServiceInterface $service): JsonResponse
    {
        return (new JsonApiRequestCoreAdapter())(
            $request,
            $service,
            [
            JsonApiRequestCoreAdapter::DTO_RESPONSE => AdListResponse::class,
        ]
        );
    }
}
