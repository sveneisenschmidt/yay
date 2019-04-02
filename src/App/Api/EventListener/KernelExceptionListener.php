<?php

namespace App\Api\EventListener;

use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use App\Api\Response\ResponseSerializer;

class KernelExceptionListener
{
    /** @var ResponseSerializer */
    protected $serializer;

    public function __construct(ResponseSerializer $serializer)
    {
        $this->serializer = $serializer;
    }

    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        return;
        $exception = $event->getException();
        $response = $this->serializer->createResponse([
            'exception' => get_class($exception),
            'message' => $exception->getMessage(),
        ]);

        if ($exception instanceof HttpExceptionInterface) {
            $response->setStatusCode($exception->getStatusCode());
        } else {
            $response->setStatusCode(500);
        }

        $event->setResponse($response);
    }
}
