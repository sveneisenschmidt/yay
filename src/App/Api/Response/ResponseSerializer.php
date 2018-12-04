<?php

namespace App\Api\Response;

use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Psr\SimpleCache\CacheInterface;

class ResponseSerializer
{
    /** @var SerializerInterface */
    protected $serializer;

    /** @var CacheInterface */
    protected $cache;

    public function __construct(
        SerializerInterface $serializer,
        CacheInterface $cache
    ) {
        $this->serializer = $serializer;
        $this->cache = $cache;
    }

    public function createResponse(
        $unserializedData,
        array $serializationGroups = [],
        int $status = 200,
        array $headers = []
    ): JsonResponse {
        $key = $this->generateCacheKey($unserializedData, $serializationGroups);

        if (!$this->cache->has($key)) {
            $context = SerializationContext::create();
            !empty($serializationGroups) ? $context->setGroups($serializationGroups) : null;

            $content = $this->serializer->serialize($unserializedData, 'json', $context);
            $this->cache->set($key, $content);
        } else {
            $content = $this->cache->get($key);
        }
        //$headers['Access-Control-Allow-Origin'] = '*';
        return new JsonResponse($content, $status, $headers, true);
    }

    public function generateCacheKey(
        $unserializedData,
        array $serializationGroups = []
    ): string {
        return implode('.', [
            'response_serializer.',
            md5(serialize($unserializedData)),
            md5(serialize($serializationGroups)),
        ]);
    }
}
