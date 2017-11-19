<?php

namespace Component\Entity\Tests;

use Faker\Factory as FakerFactory;
use PHPUnit\Framework\TestCase;
use Component\Entity\Achievement\AchievementDefinition;
use Component\Entity\Achievement\PersonalAchievement;
use Component\Entity\Achievement\PersonalAction;
use Component\Entity\Player;

class PlayerTest extends TestCase
{
    public function setUp()
    {
        $this->faker = FakerFactory::create();
    }

    /**
     * @test
     */
    public function set_get_scalar()
    {
        $instance = new Player();

        $instance->setName($name = $this->faker->name);
        $instance->setUsername($username = $this->faker->userName);
        $instance->setEmail($email = $this->faker->email);
        $instance->setScore($score = rand(1, 100));
        $instance->setImageUrl($imageUrl = 'https://example.org/example.png');

        $this->assertEquals($name, $instance->getName());
        $this->assertEquals($username, $instance->getUsername());
        $this->assertEquals($email, $instance->getEmail());
        $this->assertEquals($score, $instance->getScore());
        $this->assertEquals($imageUrl, $instance->getImageUrl());
    }

    /**
     * @test
     */
    public function set_get_personal_actions()
    {
        $instance = new Player();
        $action = $this->createMock(PersonalAction::class);

        $this->assertEmpty($instance->getPersonalActions());
        $instance->getPersonalActions()->add($action);
        $this->assertNotEmpty($instance->getPersonalActions());
        $this->assertEquals($action, $instance->getPersonalActions()->first());
    }

    /**
     * @test
     */
    public function set_get_personal_achievement()
    {
        $instance = new Player();
        $achievement = $this->createMock(PersonalAchievement::class);

        $this->assertEmpty($instance->getPersonalAchievements());
        $instance->getPersonalAchievements()->add($achievement);
        $this->assertNotEmpty($instance->getPersonalAchievements());
        $this->assertEquals($achievement, $instance->getPersonalAchievements()->first());
    }

    /**
     * @test
     */
    public function has_personal_achievement()
    {
        $instance = new Player();
        $definition = new AchievementDefinition($name = 'definition');
        $achievement = new PersonalAchievement($instance, $definition);

        $this->assertFalse($instance->hasPersonalAchievement($definition));
        $instance->getPersonalAchievements()->add($achievement);
        $this->assertTrue($instance->hasPersonalAchievement($definition));
    }

    /**
     * @test
     */
    public function refresh_score()
    {
        $instance = new Player();
        $definition = new AchievementDefinition($name = 'definition');
        $definition->setPoints(100);
        $achievement1 = new PersonalAchievement($instance, $definition);
        $achievement2 = new PersonalAchievement($instance, $definition);

        $this->assertEquals(0, $instance->refreshScore());
        $instance->getPersonalAchievements()->add($achievement1);
        $instance->getPersonalAchievements()->add($achievement2);
        $this->assertEquals(200, $instance->refreshScore());
    }
}
