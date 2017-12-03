<?php

namespace App\Api\Tests\Controller;

use App\Api\Test\WebTestCase;

class ActionControllerTest extends WebTestCase
{
    public function test_Action_IndexAction(): void
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
            $this->assertArrayHasKey('label', $value);
            $this->assertArrayNotHasKey('description', $value);
            $this->assertArraySubsetHasKey('links', 'self', $value);
        }
        
        $client->request('GET', '/api/actions/?limit=1');
        $response = $client->getResponse();
        
        $this->assertTrue($response->isOk());
        $this->assertJson($content = $response->getContent());
        $this->assertInternalType('array', $data = json_decode($content, true));
        $this->assertNotEmpty($data);
        $this->assertCount(1, $data);
    }

    public function test_Action_ShowAction(): void
    {
        $client = static::createClient();

        $client->request('GET', '/api/actions/yay.action.test_api_action/');
        $response = $client->getResponse();

        $this->assertTrue($response->isOk());
        $this->assertJson($content = $response->getContent());
        $this->assertInternalType('array', $data = json_decode($content, true));
        $this->assertNotEmpty($data);

        $this->assertArrayHasKey('name', $data);
        $this->assertArrayHasKey('label', $data);
        $this->assertArrayHasKey('description', $data);
        $this->assertArraySubsetHasKey('links', 'self', $data);
    }

    public function test_Action_ShowAction_NotFound(): void
    {
        $client = static::createClient();

        $client->request('GET', '/api/actions/yay.action.test_not-found/');
        $response = $client->getResponse();

        $this->assertTrue($response->isNotFound());
    }
}
