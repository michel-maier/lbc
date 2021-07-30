<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class JsonApiRequestCoreAdapter
{
    public const URI_VARS = 'uri-vars';
    public const RESPONSE_STATUS = 'response-status';
    public const DTO_REQUEST = 'dto-request';
    public const DTO_RESPONSE = 'dto-response';

    private Serializer $serializer;

    public function __construct()
    {
        $this->serializer = new Serializer([new ObjectNormalizer()], [new JsonEncoder()]);
    }

    public function __invoke(Request $request, callable $service, array $options): JsonResponse
    {
        $options += [
            self::URI_VARS => [],
            self::DTO_REQUEST => null,
            self::DTO_RESPONSE => null,
            self::RESPONSE_STATUS => Response::HTTP_OK,
        ];

        $data = $this->formatApplicationArguments($request, $options);

        return $this->formatResponse($service($data), $options);
    }

    private function formatApplicationArguments(Request $request, array $options): mixed
    {
        $data = [];
        foreach ($options[self::URI_VARS] as $var) {
            $data[$var] = $request->get($var);
        }

        if ($options[self::DTO_REQUEST]) {
            $data = $this->serializer->deserialize($request->getContent(), $options[self::DTO_REQUEST], JsonEncoder::FORMAT, [ObjectNormalizer::DEFAULT_CONSTRUCTOR_ARGUMENTS => [$options[self::DTO_REQUEST] => $data]]);
        } elseif (1 === count($data)) {
            $data = array_pop($data);
        }

        return $data;
    }

    private function formatResponse($response, array $options): JsonResponse
    {
        if (null !== $options[self::DTO_RESPONSE]) {
            return new JsonResponse($this->serializer->normalize($response, $options[self::DTO_RESPONSE], [ObjectNormalizer::SKIP_NULL_VALUES => true]), $options[self::RESPONSE_STATUS]);
        }

        if ($options[self::RESPONSE_STATUS]) {
            return new JsonResponse(null, $options[self::RESPONSE_STATUS]);
        }
    }
}
