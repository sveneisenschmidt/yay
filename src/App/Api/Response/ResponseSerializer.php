<?php

namespace App\Api\Response;

use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class ResponseSerializer
{
    /** @var SerializerInterface */
    protected $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    public function createResponse(
        $unserializedData,
        array $serializationGroups = [],
        int $status = 200,
        array $headers = []
    ): JsonResponse {
        $context = SerializationContext::create();
        !empty($serializationGroups) ? $context->setGroups($serializationGroups) : null;

        $content = $this->serializer->serialize($unserializedData, 'json', $context);

        return new JsonResponse($content, $status, $headers, true);
    }
}
