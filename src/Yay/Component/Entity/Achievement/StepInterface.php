<?php

namespace Yay\Component\Entity\Achievement;

use Yay\Component\Entity\Achievement\ActionDefinitionInterface;
use Yay\Component\Entity\PlayerInterface;

interface StepInterface
{
    /**
     * @param PlayerInterface $player
     *
     * @return mixed
     */
    public function setPlayer(PlayerInterface $player);

    /**
     * @return PlayerInterface
     */
    public function getPlayer(): PlayerInterface;

    /**
     * @return \DateTime
     */
    public function getAchievedAt(): \DateTime;

    /**
     * @return ActionDefinitionInterface
     */
    public function getActionDefinition(): ActionDefinitionInterface;

    /**
     * @param ActionDefinitionInterface $actionDefinition
     *
     * @return mixed
     */
    public function setActionDefinition(ActionDefinitionInterface $actionDefinition);

    /**
     * @param \DateTime $achievedAt
     *
     * @return mixed
     */
    public function setAchievedAt(\DateTime $achievedAt);

    /**
     * @return string
     */
    public function __toString(): string;
}