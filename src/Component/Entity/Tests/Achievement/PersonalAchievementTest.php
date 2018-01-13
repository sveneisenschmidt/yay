<?php

namespace Component\Entity\Tests\Achievement;

use PHPUnit\Framework\TestCase;
use Component\Entity\PlayerInterface;
use Component\Entity\Achievement\PersonalAchievement;
use Component\Entity\Achievement\AchievementDefinitionInterface;

class PersonalAchievementTest extends TestCase
{
    /**
     * @var \Faker\Generator
     */
    protected $faker;

    public function setUp(): void
    {
        $this->faker = \Faker\Factory::create();
    }

    public function test_set_get_scalar(): void
    {
        $instance = new PersonalAchievement(
            $player = $this->createMock(PlayerInterface::class),
            $achievementDefinition = $this->createConfiguredMock(AchievementDefinitionInterface::class, [
                'getName' => $name = 'test-achievement-01',
            ]),
            $achievedAt = $this->faker->dateTime()
        );

        $this->assertSame($player, $instance->getPlayer());
        $this->assertSame($achievementDefinition, $instance->getAchievementDefinition());
        $this->assertSame($achievedAt, $instance->getAchievedAt());
        $this->assertEquals($name, (string) $instance);
    }

    public function test_set_get_player(): void
    {
        $instance = new PersonalAchievement(
            $player1 = $this->createMock(PlayerInterface::class),
            $achievementDefinition = $this->createMock(AchievementDefinitionInterface::class)
        );

        $this->assertSame($player1, $instance->getPlayer());
        $player2 = $this->createMock(PlayerInterface::class);
        $instance->setPlayer($player2);
        $this->assertSame($player2, $instance->getPlayer());
    }

    public function test_set_get_achievement_definition(): void
    {
        $instance = new PersonalAchievement(
            $player = $this->createMock(PlayerInterface::class),
            $achievementDefinition1 = $this->createMock(AchievementDefinitionInterface::class)
        );

        $this->assertSame($achievementDefinition1, $instance->getAchievementDefinition());
        $achievementDefinition2 = $this->createMock(AchievementDefinitionInterface::class);
        $instance->setAchievementDefinition($achievementDefinition2);
        $this->assertSame($achievementDefinition2, $instance->getAchievementDefinition());
    }

    public function test_set_get_achieved_at(): void
    {
        $instance = new PersonalAchievement(
            $player = $this->createMock(PlayerInterface::class),
            $achievementDefinition = $this->createMock(AchievementDefinitionInterface::class),
            $achievedAt1 = $this->faker->dateTime()
        );

        $this->assertSame($achievedAt1, $instance->getAchievedAt());
        $achievedAt2 = new \DateTime();
        $instance->setAchievedAt($achievedAt2);
        $this->assertSame($achievedAt2, $instance->getAchievedAt());
    }
}
