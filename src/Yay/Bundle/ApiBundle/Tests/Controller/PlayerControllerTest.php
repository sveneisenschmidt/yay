<?php

use Yay\Bundle\ApiBundle\Test\WebTestCase;

class PlayerControllerTest extends WebTestCase
{
    /**
     * @test
     * @testdox Retrieve all players
     */
    public function Player_IndexAction()
    {
        $client = static::createClient();

        $client->request('GET', '/api/players/');
        $response = $client->getResponse();

        $this->assertTrue($response->isOk());
        $this->assertJson($content = $response->getContent());
        $this->assertInternalType('array', $data = json_decode($content, true));
        $this->assertNotEmpty($data);

        foreach ($data as $key => $value) {
            $this->assertArrayHasKey('name', $value);
            $this->assertArrayHasKey('username', $value);
            $this->assertArraySubsetHasKey('links', 'self', $value);
            $this->assertArraySubsetHasKey('links', 'personal_achievements', $value);
            $this->assertArraySubsetHasKey('links', 'personal_actions', $value);
        }
    }

    /**
     * @test
     * @testdox Retrieve a single player
     */
    public function Player_ShowAction()
    {
        $client = static::createClient();

        $client->request('GET', '/api/players/jane.doe');
        $response = $client->getResponse();

        $this->assertTrue($response->isOk());
        $this->assertJson($content = $response->getContent());
        $this->assertInternalType('array', $data = json_decode($content, true));
        $this->assertNotEmpty($data);

        $this->assertArrayHasKey('name', $data);
        $this->assertArrayHasKey('username', $data);
        $this->assertArraySubsetHasKey('links', 'self', $data);
        $this->assertArraySubsetHasKey('links', 'personal_achievements', $data);
        $this->assertArraySubsetHasKey('links', 'personal_actions', $data);
    }

    /**
     * @test
     * @testdox Retrieve a single player's personal achievements
     */
    public function Player_PersonalAchievements_IndexAction()
    {
        $client = static::createClient();

        $client->request('GET', '/api/players/jane.doe/personal-achievements');
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
    }

    /**
     * @test
     * @testdox Retrieve a single player's personal achievements
     */
    public function Player_PersonalActions_IndexAction()
    {
        $client = static::createClient();

        $client->request('GET', '/api/players/jane.doe/personal-actions');
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
            $this->assertArraySubsetHasKey('links', 'action', $value);
        }
    }
}
