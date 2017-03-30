<?php

namespace Yay\Bundle\ApiBundle\Controller;

use JMS\Serializer\SerializerInterface;
use JMS\Serializer\SerializationContext;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

use Yay\Component\Engine\Engine;

/**
 * @Method("GET")
 * @Route("/")
 */
abstract class ApiController extends Controller
{
    /**
     * @param mixed $unserializedData
     * @param array $serializaionGroups
     * @param int $status
     * @param array $headers
     *
     * @return JsonResponse
     */
    public function respond($unserializedData, $serializationGroups = [], $status = 200, array $headers = [])
    {
        $context = SerializationContext::create();
        !empty($serializationGroups) ? $context->setGroups($serializationGroups) : null;

        $content = $this->getSerializer()->serialize($unserializedData, 'json', $context);
        return new JsonResponse($content, $status, $headers, true);
    }

    /**
     * @return  object|SerializerInterface
     */
    public function getSerializer(): SerializerInterface
    {
        return $this->get('serializer');
    }

    /**
     * @return object|Engine
     */
    public function getEngine(): Engine
    {
        return $this->get('yay.engine');
    }
}
