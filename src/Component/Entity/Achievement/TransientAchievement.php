<?php

namespace Component\Entity\Achievement;

use Component\Entity\PlayerInterface;

class TransientAchievement implements TransientAchievementInterface
{
    /** @var AchievementDefinitionInterface */
    protected $achievementDefinition;

    /** @var PlayerInterface */
    protected $player;

    /** @var int */
    protected $progress;

    public function __construct(
        AchievementDefinitionInterface $achievementDefinition,
        PlayerInterface $player,
        int $progress
    ) {
        $this->achievementDefinition = $achievementDefinition;
        $this->player = $player;
        $this->progress = $progress;
    }

    public function getAchievementDefinition(): AchievementDefinitionInterface
    {
        return $this->achievementDefinition;
    }

    public function getPlayer(): PlayerInterface
    {
        return $this->player;
    }

    public function getProgress(): int
    {
        return $this->progress;
    }

    public function __toString(): string
    {
        return $this->getAchievementDefinition()->getName();
    }
}
