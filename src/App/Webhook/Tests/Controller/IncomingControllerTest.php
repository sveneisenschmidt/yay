<?php

namespace App\Webhook\Tests\Controller;

use App\Webhook\Test\WebTestCase;

class IncomingControllerTest extends WebTestCase
{
    public function test_Incoming_SubmitPostAction_Chain_Processor(): void
    {
        $client = static::createClient();

        $processor = 'test-processor-01';
        $content = [];

        $client->request('POST', sprintf('/webhook/incoming/%s/', $processor), $content);
        $response = $client->getResponse();

        $this->assertTrue($response->isOk());
        $this->assertEquals(0, $response->headers->get('X-Achievements-Granted-Count'));
    }

    public function test_Incoming_SubmitPostAction_Chain_Processor_Many(): void
    {
        $client = static::createClient();

        $processor = 'test-processor-01';
        $content = [];

        // 1. Action
        $client->request('POST', sprintf('/webhook/incoming/%s/', $processor), $content);
        $response = $client->getResponse();

        $this->assertTrue($response->isOk());
        $this->assertEquals(0, $response->headers->get('X-Achievements-Granted-Count'));

        // 2. Action
        $client->request('POST', sprintf('/webhook/incoming/%s/', $processor), $content);
        $response = $client->getResponse();
        $this->assertTrue($response->isOk());
        $this->assertEquals(0, $response->headers->get('X-Achievements-Granted-Count'));

        // 3. Action
        $client->request('POST', sprintf('/webhook/incoming/%s/', $processor), $content);
        $response = $client->getResponse();
        $this->assertTrue($response->isOk());
        $this->assertEquals(0, $response->headers->get('X-Achievements-Granted-Count'));

        // 4. Action
        $client->request('POST', sprintf('/webhook/incoming/%s/', $processor), $content);
        $response = $client->getResponse();
        $this->assertTrue($response->isOk());
        $this->assertEquals(0, $response->headers->get('X-Achievements-Granted-Count'));

        // 5. Action
        $client->request('POST', sprintf('/webhook/incoming/%s/', $processor), $content);
        $response = $client->getResponse();
        $this->assertTrue($response->isOk());
        $this->assertEquals(1, $response->headers->get('X-Achievements-Granted-Count'));
    }

    public function test_Incoming_SubmitPostAction_Valid_Processor(): void
    {
        $client = static::createClient();

        $processor = 'test-processor-02';
        $content = [
            'username' => 'jane.doe',
            'action' => 'yay.action.test_webhook_action',
        ];

        $client->request('POST', sprintf('/webhook/incoming/%s/', $processor), $content);
        $response = $client->getResponse();

        $this->assertTrue($response->isOk());
        $this->assertEquals(0, $response->headers->get('X-Achievements-Granted-Count'));
    }

    public function test_Incoming_SubmitPostAction_Invalid_Processor(): void
    {
        $client = static::createClient();

        $processor = 'test-processor-05';
        $content = [];

        $client->request('POST', sprintf('/webhook/incoming/%s/', $processor), $content);
        $response = $client->getResponse();

        $this->assertTrue($response->isNotFound());
    }

    public function test_Incoming_SubmitPostAction_Invalid_Processor_No_Player(): void
    {
        $client = static::createClient();

        $processor = 'test-processor-03';
        $content = [];

        $client->request('POST', sprintf('/webhook/incoming/%s/', $processor), $content);
        $response = $client->getResponse();

        $this->assertTrue($response->isNotFound());
    }

    public function test_Incoming_SubmitPostAction_Invalid_Processor_No_Action(): void
    {
        $client = static::createClient();

        $processor = 'test-processor-05';
        $content = [];

        $client->request('POST', sprintf('/webhook/incoming/%s/', $processor), $content);
        $response = $client->getResponse();

        $this->assertTrue($response->isNotFound());
    }

    public function test_Incoming_SubmitPostAction_Unknown_Processor(): void
    {
        $client = static::createClient();

        $processor = 'test-processor-06';
        $content = [];

        $client->request('POST', sprintf('/webhook/incoming/%s/', $processor), $content);
        $response = $client->getResponse();

        $this->assertTrue($response->isNotFound());
    }
}
