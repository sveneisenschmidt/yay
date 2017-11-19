<?php

namespace App\Api\Tests\Controller;

use App\Api\Test\WebTestCase;
use Faker\Factory as FakerFactory;

class PlayerControllerTest extends WebTestCase
{
    /**
     * Provides faked player data.
    */
    public function providePlayerData(): array
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

    public function test_Player_IndexAction(): void
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

    public function test_Player_ShowAction(): void
    {
        $client = static::createClient();

        $client->request('GET', '/api/players/jane.doe/');
        $response = $client->getResponse();

        $this->assertTrue($response->isOk());
        $this->assertJson($content = $response->getContent());
        $this->assertInternalType('array', $data = json_decode($content, true));
        $this->assertNotEmpty($data);
        $this->assertPlayerData($data);
    }

    public function test_Player_ShowAction_NotFound(): void
    {
        $client = static::createClient();

        $client->request('GET', '/api/players/not-found/');
        $response = $client->getResponse();

        $this->assertTrue($response->isNotFound());
    }

    /**
     * @dataProvider providePlayerData
     */
    public function test_Player_CreateAction(array $data): void
    {
        $client = static::createClient();

        $client->request('POST', '/api/players/', [], [], [], json_encode($data));
        $response = $client->getResponse();

        $this->assertTrue($response->isRedirect());
        $this->assertTrue($response->headers->has('Location'));
        $this->assertJson($content = $response->getContent());
        $this->assertInternalType('array', $data = json_decode($content, true));
        $this->assertNotEmpty($data);
        $this->assertPlayerData($data);
    }

    public function test_Player_CreateAction_UnprocessableEntity(): void
    {
        $client = static::createClient();

        $client->request('POST', '/api/players/', [], [], [], json_encode([]));
        $response = $client->getResponse();

        $this->assertTrue($response->isClientError());
    }

    /**
     * @dataProvider providePlayerData
     */
    public function test_Player_CreateAction_Exception_NonUniqueUsername(array $data): void
    {
        $client = static::createClient();

        $client->request('POST', '/api/players/', [], [], [], json_encode($data));
        $response = $client->getResponse();

        $this->assertTrue($response->isRedirect());

        $client->request('POST', '/api/players/', [], [], [], json_encode($data));
        $response = $client->getResponse();

        $this->assertTrue($response->isServerError());
    }

    public function test_Player_PersonalAchievements_IndexAction(): void
    {
        $client = static::createClient();

        $client->request('GET', '/api/players/jane.doe/personal-achievements/');
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

    public function test_Player_PersonalAchievements_IndexAction_NotFound(): void
    {
        $client = static::createClient();

        $client->request('GET', '/api/players/john.doe/personal-achievements/');
        $response = $client->getResponse();

        $this->assertTrue($response->isNotFound());
    }

    public function test_Player_PersonalActions_IndexAction(): void
    {
        $client = static::createClient();

        $client->request('GET', '/api/players/jane.doe/personal-actions/');
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

    public function test_Player_PersonalActions_IndexAction_NotFound(): void
    {
        $client = static::createClient();

        $client->request('GET', '/api/players/john.doe/personal-actions/');
        $response = $client->getResponse();

        $this->assertTrue($response->isNotFound());
    }
}
