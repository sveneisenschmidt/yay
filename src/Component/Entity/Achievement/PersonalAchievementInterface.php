<?php

namespace Component\Entity\Achievement;

use Component\Entity\PlayerInterface;

interface PersonalAchievementInterface
{
    /**
     * @param AchievementDefinitionInterface $achievementDefinition
     */
    public function setAchievementDefinition(AchievementDefinitionInterface $achievementDefinition);

    /**
     * @return AchievementDefinitionInterface
     */
    public function getAchievementDefinition(): AchievementDefinitionInterface;

    /**
     * @return PlayerInterface
     */
    public function getPlayer(): PlayerInterface;

    /**
     * @return string
     */
    public function __toString(): string;
}