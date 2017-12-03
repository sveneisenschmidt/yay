<?php

namespace App\Api\Tests\Controller;

use App\Api\Test\WebTestCase;
use Component\Entity\Activity;

class ActivityControllerTest extends WebTestCase
{
    public function test_Activity_IndexAction(): void
    {
        $client = static::createClient();

        $client->request('GET', '/api/activities/');
        $response = $client->getResponse();

        $this->assertTrue($response->isOk());
        $this->assertJson($content = $response->getContent());
        $this->assertInternalType('array', $data = json_decode($content, true));
        $this->assertNotEmpty($data);

        foreach ($data as $key => $value) {
            $this->assertArrayHasKey('name', $value);
            $this->assertArrayHasKey('created_at', $value);
            $this->assertArrayHasKey('data', $value);

            if (Activity::PERSONAL_ACHIEVEMENT_GRANTED == $value['name']) {
                $this->assertArraySubsetHasKey('data', 'player', $value);
                $this->assertArraySubsetHasKey('data', 'achievement', $value);
                $this->assertArraySubsetHasKey('data', 'achieved_at', $value);

                $this->assertArraySubsetHasKey('links', 'self', $value);
                $this->assertArraySubsetHasKey('links', 'player', $value);
                $this->assertArraySubsetHasKey('links', 'achievement', $value);
            }

            if (Activity::PERSONAL_ACTION_GRANTED == $value['name']) {
                $this->assertArraySubsetHasKey('data', 'player', $value);
                $this->assertArraySubsetHasKey('data', 'action', $value);
                $this->assertArraySubsetHasKey('data', 'achieved_at', $value);

                $this->assertArraySubsetHasKey('links', 'self', $value);
                $this->assertArraySubsetHasKey('links', 'player', $value);
                $this->assertArraySubsetHasKey('links', 'action', $value);
            }
        }

        $client->request('GET', '/api/activities/?limit=1');
        $response = $client->getResponse();

        $this->assertTrue($response->isOk());
        $this->assertJson($content = $response->getContent());
        $this->assertInternalType('array', $data = json_decode($content, true));
        $this->assertNotEmpty($data);
        $this->assertCount(1, $data);
    }
}
