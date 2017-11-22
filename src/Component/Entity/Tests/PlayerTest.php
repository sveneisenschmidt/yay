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
    public function setUp(): void
    {
        $this->faker = FakerFactory::create();
    }

    public function test_set_get_scalar(): void
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

    public function test_set_get_personal_actions(): void
    {
        $instance = new Player();
        $action = $this->createMock(PersonalAction::class);

        $this->assertEmpty($instance->getPersonalActions());
        $instance->getPersonalActions()->add($action);
        $this->assertNotEmpty($instance->getPersonalActions());
        $this->assertEquals($action, $instance->getPersonalActions()->first());
    }

    public function test_set_get_personal_achievement(): void
    {
        $instance = new Player();
        $achievement = $this->createMock(PersonalAchievement::class);

        $this->assertEmpty($instance->getPersonalAchievements());
        $instance->getPersonalAchievements()->add($achievement);
        $this->assertNotEmpty($instance->getPersonalAchievements());
        $this->assertEquals($achievement, $instance->getPersonalAchievements()->first());
    }

    public function test_has_personal_achievement(): void
    {
        $instance = new Player();
        $definition = new AchievementDefinition($name = 'definition');
        $achievement = new PersonalAchievement($instance, $definition);

        $this->assertFalse($instance->hasPersonalAchievement($definition));
        $instance->getPersonalAchievements()->add($achievement);
        $this->assertTrue($instance->hasPersonalAchievement($definition));
    }

    public function test_refresh_score(): void
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
