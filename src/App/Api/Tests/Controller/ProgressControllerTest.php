<?php

namespace App\Api\Tests\Controller;

use App\Api\Test\WebTestCase;

class ProgressControllerTest extends WebTestCase
{
    public function test_Progress_SubmitGetAction_ValidUser_OneAction(): void
    {
        $client = static::createClient();

        $content = http_build_query([
            'username' => 'jane.doe',
            'action' => 'yay.action.test_api_action',
        ]);

        // (1) Push new actions
        $client->request('GET', sprintf('/api/progress/?%s', $content));
        $response = $client->getResponse();

        $this->assertTrue($response->isOk());
        $this->assertJson($content = $response->getContent());
        $this->assertInternalType('array', $data = json_decode($content, true));
        $this->assertEmpty($data);
        
        // (2) Get activity list
        $client->request('GET', '/api/activities/');
        $response = $client->getResponse();

        $this->assertTrue($response->isOk());
        $this->assertJson($content = $response->getContent());
        $this->assertInternalType('array', $data = json_decode($content, true));
        $this->assertNotEmpty($data);
        $this->assertCount(1, $data);
    }

    public function test_Progress_SubmitPostAction_ValidUser_OneAction(): void
    {
        $client = static::createClient();

        $content = json_encode([
            'username' => 'jane.doe',
            'action' => 'yay.action.test_api_action',
        ]);

        // (1) Push new actions
        $client->request('POST', '/api/progress/', [], [], [], $content);
        $response = $client->getResponse();

        $this->assertTrue($response->isOk());
        $this->assertJson($content = $response->getContent());
        $this->assertInternalType('array', $data = json_decode($content, true));
        $this->assertEmpty($data);

        // (2) Get activity list
        $client->request('GET', '/api/activities/');
        $response = $client->getResponse();

        $this->assertTrue($response->isOk());
        $this->assertJson($content = $response->getContent());
        $this->assertInternalType('array', $data = json_decode($content, true));
        $this->assertNotEmpty($data);
        $this->assertCount(1, $data);
    }

    public function test_Progress_SubmitGetAction_ValidUser_ManyAction(): void
    {
        $client = static::createClient();

        $content = http_build_query([
            'username' => 'jane.doe',
            'actions' => [
                'yay.action.test_api_action',
                'yay.action.test_api_action',
                'yay.action.test_api_action',
                'yay.action.test_api_action',
                'yay.action.test_api_action',
            ],
        ]);

        // (1) Push new actions
        $client->request('GET', sprintf('/api/progress/?%s', $content));
        $response = $client->getResponse();

        $this->assertTrue($response->isOk());
        $this->assertJson($content = $response->getContent());
        $this->assertInternalType('array', $data = json_decode($content, true));
        $this->assertNotEmpty($data);

        foreach ($data as $key => $value) {
            $this->assertArrayHasKey('name', $value);
            $this->assertArrayHasKey('achieved_at', $value);
            $this->assertArraySubsetHasKey('links', 'self', $value);
            $this->assertArraySubsetHasKey('links', 'player', $value);
            $this->assertArraySubsetHasKey('links', 'achievement', $value);
        }

        // (2) Get a player's achievements
        $client->request('GET', '/api/players/jane.doe/personal-achievements/');
        $response = $client->getResponse();

        $this->assertTrue($response->isOk());
        $this->assertJson($content = $response->getContent());
        $this->assertInternalType('array', $data = json_decode($content, true));
        $this->assertNotEmpty($data);

        foreach ($data as $key => $value) {
            $this->assertArrayHasKey('name', $value);
            $this->assertArrayHasKey('achieved_at', $value);
            $this->assertArraySubsetHasKey('links', 'self', $value);
            $this->assertArraySubsetHasKey('links', 'player', $value);
            $this->assertArraySubsetHasKey('links', 'achievement', $value);
        }

        // (3) Get activity list
        $client->request('GET', '/api/activities/');
        $response = $client->getResponse();

        $this->assertTrue($response->isOk());
        $this->assertJson($content = $response->getContent());
        $this->assertInternalType('array', $data = json_decode($content, true));
        $this->assertNotEmpty($data);
        $this->assertCount(6, $data);
    }

    public function test_Progress_SubmitPostAction_ValidUser_ManyAction(): void
    {
        $client = static::createClient();

        $content = json_encode([
            'username' => 'jane.doe',
            'actions' => [
                'yay.action.test_api_action',
                'yay.action.test_api_action',
                'yay.action.test_api_action',
                'yay.action.test_api_action',
                'yay.action.test_api_action',
            ],
        ]);

        // (1) Push new actions
        $client->request('POST', '/api/progress/', [], [], [], $content);
        $response = $client->getResponse();

        $this->assertTrue($response->isOk());
        $this->assertJson($content = $response->getContent());
        $this->assertInternalType('array', $data = json_decode($content, true));
        $this->assertNotEmpty($data);

        foreach ($data as $key => $value) {
            $this->assertArrayHasKey('name', $value);
            $this->assertArrayHasKey('achieved_at', $value);
            $this->assertArraySubsetHasKey('links', 'self', $value);
            $this->assertArraySubsetHasKey('links', 'player', $value);
            $this->assertArraySubsetHasKey('links', 'achievement', $value);
        }

        // (2) Get a player's achievements
        $client->request('GET', '/api/players/jane.doe/personal-achievements/');
        $response = $client->getResponse();

        $this->assertTrue($response->isOk());
        $this->assertJson($content = $response->getContent());
        $this->assertInternalType('array', $data = json_decode($content, true));
        $this->assertNotEmpty($data);

        foreach ($data as $key => $value) {
            $this->assertArrayHasKey('name', $value);
            $this->assertArrayHasKey('achieved_at', $value);
            $this->assertArraySubsetHasKey('links', 'self', $value);
            $this->assertArraySubsetHasKey('links', 'player', $value);
            $this->assertArraySubsetHasKey('links', 'achievement', $value);
        }

        // (3) Get activity list
        $client->request('GET', '/api/activities/');
        $response = $client->getResponse();

        $this->assertTrue($response->isOk());
        $this->assertJson($content = $response->getContent());
        $this->assertInternalType('array', $data = json_decode($content, true));
        $this->assertNotEmpty($data);
        $this->assertCount(6, $data);
    }

    public function test_Progress_SubmitGetAction_ValidUser_NoAction(): void
    {
        $client = static::createClient();

        $content = http_build_query([
            'username' => 'jane.doe',
        ]);

        $client->request('GET', sprintf('/api/progress/?%s', $content));
        $response = $client->getResponse();

        $this->assertTrue($response->isNotFound());
    }

    public function test_Progress_SubmitPostAction_ValidUser_NoAction(): void
    {
        $client = static::createClient();

        $content = json_encode([
            'username' => 'jane.doe',
        ]);

        $client->request('POST', '/api/progress/', [], [], [], $content);
        $response = $client->getResponse();

        $this->assertTrue($response->isNotFound());
    }

    public function test_Progress_SubmitGetAction_ValidUser_UnknownPlayer(): void
    {
        $client = static::createClient();

        $content = http_build_query([
            'username' => 'john.doe',
            'action' => 'yay.action.test_api_action',
        ]);

        $client->request('GET', sprintf('/api/progress/?%s', $content));
        $response = $client->getResponse();

        $this->assertTrue($response->isNotFound());
    }

    public function test_Progress_SubmitPostAction_ValidUser_UnknownPlayer(): void
    {
        $client = static::createClient();

        $content = json_encode([
            'username' => 'john.doe',
            'action' => 'yay.action.test_api_action',
        ]);

        $client->request('POST', '/api/progress/', [], [], [], $content);
        $response = $client->getResponse();

        $this->assertTrue($response->isNotFound());
    }

    public function test_Progress_SubmitGetAction_ValidUser_UnknownAction(): void
    {
        $client = static::createClient();

        $content = http_build_query([
            'username' => 'jane.doe',
            'action' => 'yay.action.unknown_test_action',
        ]);

        $client->request('GET', sprintf('/api/progress/?%s', $content));
        $response = $client->getResponse();

        $this->assertTrue($response->isOk());
        $this->assertJson($content = $response->getContent());
        $this->assertInternalType('array', $data = json_decode($content, true));
        $this->assertEmpty($data);
    }

    public function test_Progress_SubmitPostAction_ValidUser_UnknownAction(): void
    {
        $client = static::createClient();

        $content = json_encode([
            'username' => 'jane.doe',
            'action' => 'yay.action.unknown_test_action',
        ]);

        $client->request('POST', '/api/progress/', [], [], [], $content);
        $response = $client->getResponse();

        $this->assertTrue($response->isOk());
        $this->assertJson($content = $response->getContent());
        $this->assertInternalType('array', $data = json_decode($content, true));
        $this->assertEmpty($data);
    }
}
