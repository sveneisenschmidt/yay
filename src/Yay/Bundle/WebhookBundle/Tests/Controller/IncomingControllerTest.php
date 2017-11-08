<?php

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class IncomingControllerTest extends WebTestCase
{
    /**
     * @test
     */
    public function Incoming_SubmitPostAction_Chain_Processor()
    {
        $client = static::createClient();

        $processor = 'test-processor-01';
        $content = [];

        $client->request('POST', sprintf('/webhook/incoming/%s/', $processor), $content);
        $response = $client->getResponse();

        $this->assertTrue($response->isRedirect());
        $this->assertTrue($response->headers->has('location'));
        $this->assertEquals(
            '/api/progress/?player=jane.doe%2C&actions%5B0%5D=test-action',
            $response->headers->get('location')
        );
    }

    /**
     * @test
     */
    public function Incoming_SubmitPostAction_Valid_Processor()
    {
        $client = static::createClient();

        $processor = 'test-processor-02';
        $content = [
            'player' => 'jane.doe',
            'action' => 'yay.action.test_action',
        ];

        $client->request('POST', sprintf('/webhook/incoming/%s/', $processor), $content);
        $response = $client->getResponse();

        $this->assertTrue($response->isRedirect());
        $this->assertTrue($response->headers->has('location'));
        $this->assertEquals(
            '/api/progress/?player=jane.doe%2C&actions%5B0%5D=test-action',
            $response->headers->get('location')
        );
    }

    /**
     * @test
     */
    public function Incoming_SubmitPostAction_Invalid_Processor()
    {
        $client = static::createClient();

        $processor = 'test-processor-05';
        $content = [];

        $client->request('POST', sprintf('/webhook/incoming/%s/', $processor), $content);
        $response = $client->getResponse();

        $this->assertTrue($response->isNotFound());
    }

    /**
     * @test
     */
    public function Incoming_SubmitPostAction_Invalid_Processor_No_Player()
    {
        $client = static::createClient();

        $processor = 'test-processor-03';
        $content = [];

        $client->request('POST', sprintf('/webhook/incoming/%s/', $processor), $content);
        $response = $client->getResponse();

        $this->assertTrue($response->isNotFound());
    }

    /**
     * @test
     */
    public function Incoming_SubmitPostAction_Invalid_Processor_No_Actions()
    {
        $client = static::createClient();

        $processor = 'test-processor-04';
        $content = [];

        $client->request('POST', sprintf('/webhook/incoming/%s/', $processor), $content);
        $response = $client->getResponse();

        $this->assertTrue($response->isNotFound());
    }

    /**
     * @test
     */
    public function Incoming_SubmitPostAction_Unknown_Processor()
    {
        $client = static::createClient();

        $processor = 'test-processor-06';
        $content = [];

        $client->request('POST', sprintf('/webhook/incoming/%s/', $processor), $content);
        $response = $client->getResponse();

        $this->assertTrue($response->isNotFound());
    }
}
