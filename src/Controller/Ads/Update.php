<?php

namespace App\Controller\Ads;

use App\Controller\JsonApiRequestCoreAdapter;
use App\Core\Ads\Application\DefaultAdResponse;
use App\Core\Ads\Application\UpdateAdRequest;
use App\Core\Ads\Application\UpdateAdServiceInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/ads/{id}', name: 'ads_update', methods: ['PATCH'])]
class Update
{
    public function __invoke(string $id, Request $request, UpdateAdServiceInterface $service): JsonResponse
    {
        return (new JsonApiRequestCoreAdapter())(
            $request,
            $service,
            [
            JsonApiRequestCoreAdapter::URI_VARS => ['id'],
            JsonApiRequestCoreAdapter::DTO_REQUEST => UpdateAdRequest::class,
            JsonApiRequestCoreAdapter::DTO_RESPONSE => DefaultAdResponse::class,
        ]
        );
    }
}
