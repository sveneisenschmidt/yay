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

        // Clean exiting configuration
        $response->headers->remove('Access-Control-Allow-Origin');
        $response->headers->remove('Vary');

        if (!$request->headers->has('Origin') || $request->headers->get('Origin') == $request->getSchemeAndHttpHost()) {
            return;
        }

        if (empty($this->allowedOrigins)) {
            return;
        }

        $origin = $request->headers->get('Origin');

        // Origin is explicitly set inside allowed origins
        if (in_array($origin, $this->allowedOrigins)) {
            $response->headers->set('Access-Control-Allow-Origin', $origin, true);
            $response->headers->set('Vary', 'Origin', true);
        }

        // Allowed Origins is a wildcard, send Cary back
        if ($this->allowedOrigins === ['*']) {
            $response->headers->set('Access-Control-Allow-Origin', $origin, true);
        }
    }
}
