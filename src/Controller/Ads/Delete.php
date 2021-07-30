<?php

namespace App\Controller\Ads;

use App\Controller\JsonApiRequestCoreAdapter;
use App\Core\Ads\Application\RemoveAdServiceInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/ads/{id}', name: 'ads_delete', methods: ['DELETE'])]
class Delete
{
    public function __invoke(Request $request, RemoveAdServiceInterface $service): JsonResponse
    {
        return (new JsonApiRequestCoreAdapter())(
            $request,
            $service,
            [
            JsonApiRequestCoreAdapter::URI_VARS => ['id'],
            JsonApiRequestCoreAdapter::RESPONSE_STATUS => Response::HTTP_NO_CONTENT,
        ]
        );
    }
}
