<?php

namespace App\Controller\Ads;

use App\Controller\JsonApiRequestCoreAdapter;
use App\Core\Ads\Application\DefaultAdResponse;
use App\Core\Ads\Application\GetAdServiceInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/ads/{id}', name: 'ads_get', methods: ['GET'])]
class Get
{
    public function __invoke(Request $request, GetAdServiceInterface $service): JsonResponse
    {
        return (new JsonApiRequestCoreAdapter())(
            $request,
            $service,
            [
            JsonApiRequestCoreAdapter::URI_VARS => ['id'],
            JsonApiRequestCoreAdapter::DTO_RESPONSE => DefaultAdResponse::class,
        ]
        );
    }
}
