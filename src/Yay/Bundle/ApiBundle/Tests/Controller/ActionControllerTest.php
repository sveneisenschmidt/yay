<?php

use Yay\Bundle\ApiBundle\Test\WebTestCase;

class ActionControllerTest extends WebTestCase
{
    /**
     * @test
     * @testdox Retrieve all actions
     */
    public function Action_IndexAction()
    {
        $client = static::createClient();

        $client->request('GET', '/api/actions/');
        $response = $client->getResponse();

        $this->assertTrue($response->isOk());
        $this->assertJson($content = $response->getContent());
        $this->assertInternalType('array', $data = json_decode($content, true));
        $this->assertNotEmpty($data);

        foreach ($data as $key => $value) {
            $this->assertArrayHasKey('name', $value);
            $this->assertArraySubsetHasKey('links', 'self', $value);
        }
    }
    /**
     * @test
     * @testdox Retrieve a single action
     */
    public function Action_ShowAction()
    {
        $client = static::createClient();

        $client->request('GET', '/api/actions/yay.action.test_action');
        $response = $client->getResponse();

        $this->assertTrue($response->isOk());
        $this->assertJson($content = $response->getContent());
        $this->assertInternalType('array', $data = json_decode($content, true));
        $this->assertNotEmpty($data);

        $this->assertArrayHasKey('name', $data);
        $this->assertArraySubsetHasKey('links', 'self', $data);
    }
}
