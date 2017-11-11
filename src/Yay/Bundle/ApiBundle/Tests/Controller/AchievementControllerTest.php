<?php

namespace Yay\Bundle\ApiBundle\Tests\Controller;

use Yay\Bundle\ApiBundle\Test\WebTestCase;

class AchievementControllerTest extends WebTestCase
{
    /**
     * @test
     * @testdox Retrieve all achievements
     */
    public function Achievement_IndexAction()
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

    /**
     * @test
     * @testdox Retrieve a single achievement
     */
    public function Achievement_ShowAction()
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

    /**
     * @test
     * @testdox Could not find a single achievement
     */
    public function Achievement_ShowAction_NotFound()
    {
        $client = static::createClient();

        $client->request('GET', '/api/achievements/yay.achievement.test_not-found/');
        $response = $client->getResponse();

        $this->assertTrue($response->isNotFound());
    }
}
