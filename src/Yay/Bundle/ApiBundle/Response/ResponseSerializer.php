<?php

namespace Yay\Bundle\ApiBundle\Response;

use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class ResponseSerializer
{
    /**
     *
     * @param SerializerInterface $serializer
     */
    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * @param mixed $unserializedData
     * @param array $serializaionGroups
     * @param int $status
     * @param array $headers
     *
     * @return JsonResponse
     */
    public function createResponse($unserializedData, $serializationGroups = [], $status = 200, array $headers = []): JsonResponse
    {
        $context = SerializationContext::create();
        !empty($serializationGroups) ? $context->setGroups($serializationGroups) : null;

        $content = $this->serializer->serialize($unserializedData, 'json', $context);
        return new JsonResponse($content, $status, $headers, true);
    }

}
