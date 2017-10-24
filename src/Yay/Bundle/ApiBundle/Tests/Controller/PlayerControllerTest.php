<?php

use Yay\Bundle\ApiBundle\Test\WebTestCase;
use Faker\Factory as FakerFactory;

class PlayerControllerTest extends WebTestCase
{
    /**
     * Provides faked player data.
     *
     * @return array
     */
    public function providePlayerData()
    {
        $faker = FakerFactory::create();

        return [
            [
                [
                    'name' => $faker->name,
                    'username' => $faker->userName,
                    'email' => $faker->email,
                    'image_url' => sprintf('https://api.adorable.io/avatars/128/%s', random_int(100, 999)),
                ],
            ],
        ];
    }

    /**
     * @param array $data
     */
    public function assertPlayerData(array $data)
    {
        $this->assertArrayHasKey('name', $data);
        $this->assertArrayHasKey('username', $data);
        $this->assertArrayHasKey('score', $data);
        $this->assertArrayHasKey('image_url', $data);
        $this->assertArraySubsetHasKey('links', 'self', $data);
        $this->assertArraySubsetHasKey('links', 'personal_achievements', $data);
        $this->assertArraySubsetHasKey('links', 'personal_actions', $data);
    }

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
            $this->assertPlayerData($value);
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
        $this->assertPlayerData($data);
    }

    /**
     * @test
     * @testdox Could not find a single player
     */
    public function Player_ShowAction_NotFound()
    {
        $client = static::createClient();

        $client->request('GET', '/api/players/not-found');
        $response = $client->getResponse();

        $this->assertTrue($response->isNotFound());
    }

    /**
     * @test
     * @dataProvider providePlayerData
     * @testdox Create a new player
     */
    public function Player_CreateAction(array $data)
    {
        $client = static::createClient();

        $client->request('POST', '/api/players/create', [], [], [], json_encode($data));
        $response = $client->getResponse();

        $this->assertTrue($response->isRedirect());
        $this->assertTrue($response->headers->has('Location'));
        $this->assertJson($content = $response->getContent());
        $this->assertInternalType('array', $data = json_decode($content, true));
        $this->assertNotEmpty($data);
        $this->assertPlayerData($data);
    }

    /**
     * @test
     * @testdox Could not create a new player
     */
    public function Player_CreateAction_UnprocessableEntity()
    {
        $client = static::createClient();

        $client->request('POST', '/api/players/create', [], [], [], json_encode([]));
        $response = $client->getResponse();

        $this->assertTrue($response->isClientError());
    }

    /**
     * @test
     * @dataProvider providePlayerData
     * @testdox Create a new player
     */
    public function Player_CreateAction_Exception_NonUniqueUsername(array $data)
    {
        $client = static::createClient();

        $client->request('POST', '/api/players/create', [], [], [], json_encode($data));
        $response = $client->getResponse();

        $this->assertTrue($response->isRedirect());

        $client->request('POST', '/api/players/create', [], [], [], json_encode($data));
        $response = $client->getResponse();

        $this->assertTrue($response->isServerError());
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
            $this->assertArrayHasKey('points', $value);
            $this->assertArraySubsetHasKey('links', 'self', $value);
            $this->assertArraySubsetHasKey('links', 'player', $value);
            $this->assertArraySubsetHasKey('links', 'achievement', $value);
        }
    }

    /**
     * @test
     * @testdox Could not find a single player's  personal achievements
     */
    public function Player_PersonalAchievements_IndexAction_NotFound()
    {
        $client = static::createClient();

        $client->request('GET', '/api/players/john.doe/personal-achievements');
        $response = $client->getResponse();

        $this->assertTrue($response->isNotFound());
    }

    /**
     * @test
     * @testdox Retrieve a single player's personal action
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

    /**
     * @test
     * @testdox Could not find a single player's  personal achievements
     */
    public function Player_PersonalActions_IndexAction_NotFound()
    {
        $client = static::createClient();

        $client->request('GET', '/api/players/john.doe/personal-actions');
        $response = $client->getResponse();

        $this->assertTrue($response->isNotFound());
    }
}
