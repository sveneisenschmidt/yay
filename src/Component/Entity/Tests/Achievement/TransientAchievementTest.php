<?php

namespace Component\Entity\Tests\Achievement;

use Component\Entity\Achievement\AchievementDefinitionInterface;
use Component\Entity\Achievement\TransientAchievement;
use Component\Entity\PlayerInterface;
use PHPUnit\Framework\TestCase;

class TransientAchievementTest extends TestCase
{
    public function test_set_get_scalar(): void
    {
        $instance = new TransientAchievement(
            $achievementDefinition = $this->createMock(AchievementDefinitionInterface::class),
            $player = $this->createMock(PlayerInterface::class),
            $progress = rand(1, 100)
        );

        $this->assertEquals($progress, $instance->getProgress());
        $this->assertEquals($player, $instance->getPlayer());
        $this->assertEquals($achievementDefinition, $instance->getAchievementDefinition());
    }
}
