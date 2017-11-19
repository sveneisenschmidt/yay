<?php

namespace Component\Entity\Achievement;

use Component\Entity\PlayerInterface;

interface PersonalAchievementInterface
{
    /**
     */
    public function setAchievementDefinition(AchievementDefinitionInterface $achievementDefinition);

    /**/
    public function getAchievementDefinition(): AchievementDefinitionInterface;

    /**/
    public function getPlayer(): PlayerInterface;

    /**/
    public function __toString(): string;
}
