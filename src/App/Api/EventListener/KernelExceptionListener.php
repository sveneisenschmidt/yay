<?php
namespace App\Api\EventListener;

use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use App\Api\Response\ResponseSerializer;

class KernelExceptionListener
{
    public function __construct(ResponseSerializer $serializer)
    {
        $this->serializer = $serializer;
    }

    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        $exception = $event->getException();
        $response = $this->serializer->createResponse([
            'exception' => get_class($exception),
            'message' => $exception->getMessage()
        ]);

        if ($exception instanceof HttpExceptionInterface) {
            $response->setStatusCode($exception->getStatusCode());
        } else {
            $response->setStatusCode(500);
        }

        $event->setResponse($response);
    }
}