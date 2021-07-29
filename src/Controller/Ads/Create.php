<?php

namespace App\Controller\Ads;

use App\Controller\JsonApiRequestCoreAdapter;
use App\Core\Ads\Application\DefaultAdResponse;
use App\Core\Ads\Application\NewAdRequest;
use App\Core\Ads\Application\NewAdServiceInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/ads', name: 'ads_create', methods: ['POST'])]
class Create
{
    public function __invoke(Request $request, NewAdServiceInterface $service): JsonResponse
    {
        return (new JsonApiRequestCoreAdapter())(
            $request,
            $service, [
            JsonApiRequestCoreAdapter::DTO_REQUEST => NewAdRequest::class,
            JsonApiRequestCoreAdapter::DTO_RESPONSE => DefaultAdResponse::class,
            JsonApiRequestCoreAdapter::RESPONSE_STATUS => Response::HTTP_CREATED,
        ]);
    }
}