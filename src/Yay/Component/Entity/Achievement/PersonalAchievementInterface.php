<?php

namespace Yay\Component\Entity\Achievement;

use Yay\Component\Entity\Achievement\AchievementDefinitionInterface;
use Yay\Component\Entity\PlayerInterface;

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
