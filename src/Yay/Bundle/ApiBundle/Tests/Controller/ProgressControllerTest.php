<?php

use Yay\Bundle\ApiBundle\Test\WebTestCase;

class ProgressControllerTest extends WebTestCase
{
    /**
     * @test
     * @testdox Submit a payload to update a users progress
     */
    public function Progress_SubmitAction_ValidUser_OneAction()
    {
        $client = static::createClient();

        $content = json_encode([
            'player' => 'jane.doe',
            'action' => 'yay.action.test_action',
        ]);

        $client->request('POST', '/api/progress/', [], [], [], $content);
        $response = $client->getResponse();

        $this->assertTrue($response->isOk());
        $this->assertJson($content = $response->getContent());
        $this->assertInternalType('array', $data = json_decode($content, true));
        $this->assertEmpty($data);
    }

    /**
     * @test
     * @testdox Submit a payload to update a users progress
     */
    public function Progress_SubmitAction_ValidUser_ManyAction()
    {
        $client = static::createClient();

        $content = json_encode([
            'player' => 'jane.doe',
            'actions' => [
                'yay.action.test_action',
                'yay.action.test_action',
                'yay.action.test_action',
                'yay.action.test_action',
                'yay.action.test_action',
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
     * @testdox Submit a payload, but missing actions, to update a users progress
     */
    public function Progress_SubmitAction_ValidUser_NoAction()
    {
        $client = static::createClient();

        $content = json_encode([
            'player' => 'jane.doe'
        ]);

        $client->request('POST', '/api/progress/', [], [], [], $content);
        $response = $client->getResponse();

        $this->assertTrue($response->isNotFound());
    }

    /**
     * @test
     * @testdox Submit a payload, but unkown player, to update a users progress
     */
    public function Progress_SubmitAction_ValidUser_UnknownPlayer()
    {
        $client = static::createClient();

        $content = json_encode([
            'player' => 'john.doe',
            'action' => 'yay.action.test_action',
        ]);

        $client->request('POST', '/api/progress/', [], [], [], $content);
        $response = $client->getResponse();

        $this->assertTrue($response->isNotFound());
    }

    /**
     * @test
     * @testdox Submit a payload, but missing actions, to update a users progress
     */
    public function Progress_SubmitAction_ValidUser_UnknownAction()
    {
        $client = static::createClient();

        $content = json_encode([
            'player' => 'jane.doe',
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
