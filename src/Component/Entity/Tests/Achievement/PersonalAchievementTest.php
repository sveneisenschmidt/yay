<?php

namespace Component\Entity\Tests\Achievement;

use Faker\Factory as FakerFactory;
use PHPUnit\Framework\TestCase;
use Component\Entity\PlayerInterface;
use Component\Entity\Achievement\PersonalAchievement;
use Component\Entity\Achievement\AchievementDefinitionInterface;

class PersonalAchievementTest extends TestCase
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

    /**
     * @test
     */
    public function set_get_player()
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

    /**
     * @test
     */
    public function set_get_achievement_definition()
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

    /**
     * @test
     */
    public function set_get_achieved_at()
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
