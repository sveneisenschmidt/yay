<?php

namespace Component\Entity\Achievement;

use Component\Entity\PlayerInterface;

class PersonalAction implements PersonalActionInterface
{
    /* @var int */
    protected $id;

    /* @var \DateTime */
    protected $achievedAt;

    /* @var ActionDefinitionInterface */
    protected $actionDefinition;

    /* @var PlayerInterface */
    protected $player;
    public function __construct(
        PlayerInterface $player,
        ActionDefinitionInterface $actionDefinition,
        \DateTime $achievedAt = null
    ) {
        $this->setPlayer($player);
        $this->setActionDefinition($actionDefinition);
        $this->setAchievedAt($achievedAt ?: new \DateTime());
    }

    public function getAchievedAt(): \DateTime
    {
        return $this->achievedAt;
    }

    public function getActionDefinition(): ActionDefinitionInterface
    {
        return $this->actionDefinition;
    }

    public function setAchievedAt(\DateTime $achievedAt): void
    {
        $this->achievedAt = $achievedAt;
    }

    public function setActionDefinition(ActionDefinitionInterface $actionDefinition): void
    {
        $this->actionDefinition = $actionDefinition;
    }

    public function setPlayer(PlayerInterface $player): void
    {
        $this->player = $player;
    }

    public function getPlayer(): PlayerInterface
    {
        return $this->player;
    }

    public function __toString(): string
    {
        return $this->getActionDefinition()->getName();
    }
}
