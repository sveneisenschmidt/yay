<?php

namespace Component\Entity\Tests\Achievement;

use Faker\Factory as FakerFactory;
use PHPUnit\Framework\TestCase;
use Component\Entity\PlayerInterface;
use Component\Entity\Achievement\PersonalAction;
use Component\Entity\Achievement\ActionDefinitionInterface;

class PersonalActionTest extends TestCase
{
    public function setUp(): void
    {
        $this->faker = FakerFactory::create();
    }

    public function test_set_get_scalar(): void
    {
        $instance = new PersonalAction(
            $player = $this->createMock(PlayerInterface::class),
            $actionDefinition = $this->createConfiguredMock(ActionDefinitionInterface::class, [
                'getName' => $name = 'test-action-01',
            ]),
            $achievedAt = $this->faker->dateTime()
        );

        $this->assertSame($player, $instance->getPlayer());
        $this->assertSame($actionDefinition, $instance->getActionDefinition());
        $this->assertSame($achievedAt, $instance->getAchievedAt());
        $this->assertEquals($name, (string) $instance);
    }

    public function test_set_get_player(): void
    {
        $instance = new PersonalAction(
            $player1 = $this->createMock(PlayerInterface::class),
            $actionDefinition = $this->createMock(ActionDefinitionInterface::class)
        );

        $this->assertSame($player1, $instance->getPlayer());
        $player2 = $this->createMock(PlayerInterface::class);
        $instance->setPlayer($player2);
        $this->assertSame($player2, $instance->getPlayer());
    }

    public function test_set_get_achievement_definition(): void
    {
        $instance = new PersonalAction(
            $player = $this->createMock(PlayerInterface::class),
            $actionDefinition1 = $this->createMock(ActionDefinitionInterface::class)
        );

        $this->assertSame($actionDefinition1, $instance->getActionDefinition());
        $actionDefinition2 = $this->createMock(ActionDefinitionInterface::class);
        $instance->setActionDefinition($actionDefinition2);
        $this->assertSame($actionDefinition2, $instance->getActionDefinition());
    }

    public function test_set_get_achieved_at(): void
    {
        $instance = new PersonalAction(
            $player = $this->createMock(PlayerInterface::class),
            $actionDefinition = $this->createMock(ActionDefinitionInterface::class),
            $achievedAt1 = $this->faker->dateTime()
        );

        $this->assertSame($achievedAt1, $instance->getAchievedAt());
        $achievedAt2 = new \DateTime();
        $instance->setAchievedAt($achievedAt2);
        $this->assertSame($achievedAt2, $instance->getAchievedAt());
    }
}
