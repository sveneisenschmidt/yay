<?php

namespace App\Api\Tests\Controller;

use App\Api\Test\WebTestCase;

class LevelControllerTest extends WebTestCase
{
    public function test_Level_IndexAction(): void
    {
        $client = static::createClient();

        $client->request('GET', '/api/levels/');
        $response = $client->getResponse();

        $this->assertTrue($response->isOk());
        $this->assertJson($content = $response->getContent());
        $this->assertInternalType('array', $data = json_decode($content, true));
        $this->assertNotEmpty($data);

        foreach ($data as $value) {
            $this->assertArrayHasKey('name', $value);
            $this->assertArrayHasKey('label', $value);
            $this->assertArrayHasKey('description', $value);
            $this->assertArrayHasKey('points', $value);
            $this->assertArrayHasKey('level', $value);
        }
    }
}
