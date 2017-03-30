<?php

namespace Yay\Component\Entity\Achievement;

use Yay\Component\Entity\Achievement\GoalDefinitionInterface;
use Yay\Component\Entity\PlayerInterface;

interface PersonalAchievementInterface
{
    /**
     * @param GoalDefinitionInterface $goalDefinition
     */
    public function setGoalDefinition(GoalDefinitionInterface $goalDefinition);

    /**
     * @return GoalDefinitionInterface
     */
    public function getGoalDefinition(): GoalDefinitionInterface;

    /**
     * @return PlayerInterface
     */
    public function getPlayer(): PlayerInterface;

    /**
     * @return string
     */
    public function __toString(): string;
}
