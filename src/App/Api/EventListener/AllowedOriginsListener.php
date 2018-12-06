<?php

namespace App\Api\EventListener;

use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use App\Api\Response\ResponseSerializer;

class AllowedOriginsListener
{
    /** @var array */
    protected $allowedOrigins = [];

    public function __construct(array $allowedOrigins = [])
    {
        $this->allowedOrigins = $allowedOrigins;
    }

    public function onKernelResponse(FilterResponseEvent $event)
    {
        if (HttpKernelInterface::MASTER_REQUEST !== $event->getRequestType()) {
            return;
        }

        $request = $event->getRequest();
        $response = $event->getResponse();
        $response->headers->remove('Access-Control-Allow-Origin');

        if (!$request->headers->has('Origin') || $request->headers->get('Origin') == $request->getSchemeAndHttpHost()) {
            return;
        }

        if (empty($this->allowedOrigins)) {
            return;
        }

        $origin = $request->headers->get('Origin');
        if (in_array($origin, $this->allowedOrigins)) {
            $response->headers->set('Access-Control-Allow-Origin', $origin, true);
        }
    }
}
