<?php

namespace Component\Entity\Achievement;

use Component\Entity\PlayerInterface;

interface TransientAchievementInterface
{
    public function getAchievementDefinition(): AchievementDefinitionInterface;

    public function getPlayer(): PlayerInterface;

    public function getProgress(): int;
}
