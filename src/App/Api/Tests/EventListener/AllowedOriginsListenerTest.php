<?php

namespace App\Api\Tests\EventListener;

use App\Api\EventListener\AllowedOriginsListener;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;

class AllowedOriginsListenerTest extends WebTestCase
{
    public function test_allowed_origins_valid(): void
    {
        $client = static::createClient();
        $listener = new AllowedOriginsListener([
            'https://test.example.org',
        ]);

        $event = new FilterResponseEvent(
            $client->getKernel(),
            $request = new Request(),
            HttpKernelInterface::MASTER_REQUEST,
            $response = new Response()
        );

        $this->assertFalse($response->headers->has('Access-Control-Allow-Origin'));
        $this->assertFalse($response->headers->has('Origin'));

        $request->headers->set('Origin', 'https://test.example.org');
        $listener->onKernelResponse($event);

        $this->assertEquals('https://test.example.org', $response->headers->get('Access-Control-Allow-Origin'));
        $this->assertEquals('Origin', $response->headers->get('Vary'));
    }

    public function test_allowed_origins_invalid(): void
    {
        $client = static::createClient();
        $listener = new AllowedOriginsListener([
            'https://test.example.org',
        ]);

        $event = new FilterResponseEvent(
            $client->getKernel(),
            $request = new Request(),
            HttpKernelInterface::MASTER_REQUEST,
            $response = new Response()
        );

        $request->headers->set('Origin', 'https://test.example.com');
        $listener->onKernelResponse($event);

        $this->assertFalse($response->headers->has('Access-Control-Allow-Origin'));
        $this->assertFalse($response->headers->has('Origin'));
    }

    public function test_not_master_request(): void
    {
        $client = static::createClient();
        $listener = new AllowedOriginsListener([
            'https://test.example.org',
        ]);

        $event = new FilterResponseEvent(
            $client->getKernel(),
            $request = new Request(),
            HttpKernelInterface::SUB_REQUEST,
            $response = new Response()
        );

        $request->headers->set('Origin', 'https://test.example.org');
        $listener->onKernelResponse($event);

        $this->assertFalse($response->headers->has('Access-Control-Allow-Origin'));
        $this->assertFalse($response->headers->has('Origin'));
    }

    public function test_no_allowed_origins_set(): void
    {
        $client = static::createClient();
        $listener = new AllowedOriginsListener();

        $event = new FilterResponseEvent(
            $client->getKernel(),
            $request = new Request(),
            HttpKernelInterface::MASTER_REQUEST,
            $response = new Response()
        );

        $request->headers->set('Origin', 'https://test.example.org');
        $listener->onKernelResponse($event);

        $this->assertFalse($response->headers->has('Access-Control-Allow-Origin'));
        $this->assertFalse($response->headers->has('Origin'));
    }

    public function test_no_origin_header_set(): void
    {
        $client = static::createClient();
        $listener = new AllowedOriginsListener([
            'https://test.example.org',
        ]);

        $event = new FilterResponseEvent(
            $client->getKernel(),
            $request = new Request(),
            HttpKernelInterface::MASTER_REQUEST,
            $response = new Response()
        );

        $listener->onKernelResponse($event);

        $this->assertFalse($response->headers->has('Access-Control-Allow-Origin'));
        $this->assertFalse($response->headers->has('Origin'));
    }

    public function test_allowed_origin_is_wildcard(): void
    {
        $client = static::createClient();
        $listener = new AllowedOriginsListener([
            '*',
        ]);

        $event = new FilterResponseEvent(
            $client->getKernel(),
            $request = new Request(),
            HttpKernelInterface::MASTER_REQUEST,
            $response = new Response()
        );

        $request->headers->set('Origin', 'https://test.example.org');
        $listener->onKernelResponse($event);

        $this->assertEquals('https://test.example.org', $response->headers->get('Access-Control-Allow-Origin'));
        $this->assertFalse($response->headers->has('Origin'));
    }
}
