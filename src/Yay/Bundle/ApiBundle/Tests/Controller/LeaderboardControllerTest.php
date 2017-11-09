<?php

namespace Yay\Bundle\ApiBundle\Tests\Controller;

use Yay\Bundle\ApiBundle\Test\WebTestCase;

class LeaderboardControllerTest extends WebTestCase
{
    /**
     * @test
     * @testdox Retrieve all players
     */
    public function Leaderboard_IndexAction()
    {
        $client = static::createClient();

        $client->request('GET', '/api/leaderboard/');
        $response = $client->getResponse();

        $this->assertTrue($response->isOk());
        $this->assertJson($content = $response->getContent());
        $this->assertInternalType('array', $data = json_decode($content, true));
        $this->assertNotEmpty($data);

        $actualScores = [];
        foreach ($data as $key => $value) {
            $this->assertArrayHasKey('name', $value);
            $this->assertArrayHasKey('username', $value);
            $this->assertArrayHasKey('score', $value);
            $this->assertArraySubsetHasKey('links', 'self', $value);
            $this->assertArraySubsetHasKey('links', 'personal_achievements', $value);
            $this->assertArraySubsetHasKey('links', 'personal_actions', $value);

            $actualPoints[] = $value['score'];
        }

        $expectedScores = $actualScores;
        rsort($expectedScores);

        $this->assertEquals($expectedScores, $actualScores, 'Failed asserting that arrays are sorted equally');
    }
}
