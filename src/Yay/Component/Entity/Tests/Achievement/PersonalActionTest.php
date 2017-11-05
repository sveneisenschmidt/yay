<?php

namespace Yay\Component\Entity\Tests\Achievement;

use Faker\Factory as FakerFactory;
use PHPUnit\Framework\TestCase;
use Yay\Component\Entity\PlayerInterface;
use Yay\Component\Entity\Achievement\PersonalAction;
use Yay\Component\Entity\Achievement\ActionDefinitionInterface;

class PersonalActionTest extends TestCase
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

    /**
     * @test
     */
    public function set_get_player()
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

    /**
     * @test
     */
    public function set_get_achievement_definition()
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

    /**
     * @test
     */
    public function set_get_achieved_at()
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
