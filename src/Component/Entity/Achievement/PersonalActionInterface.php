<?php

namespace Component\Entity\Achievement;

use Component\Entity\PlayerInterface;

interface PersonalActionInterface
{
    public function setPlayer(PlayerInterface $player);

    /**/
    public function getPlayer(): PlayerInterface;

    /**/
    public function getAchievedAt(): \DateTime;

    /**/
    public function getActionDefinition(): ActionDefinitionInterface;

    public function setActionDefinition(ActionDefinitionInterface $actionDefinition);

    public function setAchievedAt(\DateTime $achievedAt);

    /**/
    public function __toString(): string;
}
