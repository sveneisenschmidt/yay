<?php

namespace App\Api\Tests\Controller;

use App\Api\Test\WebTestCase;

class AchievementControllerTest extends WebTestCase
{
    public function test_Achievement_IndexAction(): void
    {
        $client = static::createClient();

        $client->request('GET', '/api/achievements/');
        $response = $client->getResponse();

        $this->assertTrue($response->isOk());
        $this->assertJson($content = $response->getContent());
        $this->assertInternalType('array', $data = json_decode($content, true));
        $this->assertNotEmpty($data);

        foreach ($data as $key => $value) {
            $this->assertArrayHasKey('name', $value);
            $this->assertArrayHasKey('label', $value);
            $this->assertArrayHasKey('points', $value);
            $this->assertArrayNotHasKey('description', $value);
            $this->assertArraySubsetHasKey('links', 'self', $value);
            $this->assertArraySubsetHasKey('links', 'actions', $value);
        }
    }

    public function test_Achievement_ShowAction(): void
    {
        $client = static::createClient();

        $client->request('GET', '/api/achievements/yay.achievement.test_api_achievement/');
        $response = $client->getResponse();

        $this->assertTrue($response->isOk());
        $this->assertJson($content = $response->getContent());
        $this->assertInternalType('array', $data = json_decode($content, true));
        $this->assertNotEmpty($data);

        $this->assertArrayHasKey('name', $data);
        $this->assertArrayHasKey('label', $data);
        $this->assertArrayHasKey('description', $data);
        $this->assertArrayHasKey('points', $data);
        $this->assertArraySubsetHasKey('links', 'self', $data);
        $this->assertArraySubsetHasKey('links', 'actions', $data);
    }

    public function test_Achievement_ShowAction_NotFound(): void
    {
        $client = static::createClient();

        $client->request('GET', '/api/achievements/yay.achievement.test_not-found/');
        $response = $client->getResponse();

        $this->assertTrue($response->isNotFound());
    }
}
