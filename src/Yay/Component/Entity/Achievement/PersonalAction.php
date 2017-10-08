<?php

namespace Yay\Component\Entity\Achievement;

use Yay\Component\Entity\Achievement\ActionDefinition;
use Yay\Component\Entity\Achievement\ActionDefinitionInterface;
use Yay\Component\Entity\Achievement\PersonalActionInterface;
use Yay\Component\Entity\Player;
use Yay\Component\Entity\PlayerInterface;

class PersonalAction implements PersonalActionInterface
{
    /**
     * @var integer
     */
    protected $id;

    /**
     * @var \DateTime
     */
    protected $achievedAt;

    /**
     * @var ActionDefinitionInterface
     */
    protected $actionDefinition;

    /**
     * @var PlayerInterface
     */
    protected $player;

    /**
     * AchievementPersonalAction constructor.
     *
     * @param PlayerInterface           $user
     * @param ActionDefinitionInterface $actionDefinition
     * @param \DateTime|null            $achievedAt
     */
    public function __construct(
        PlayerInterface $player,
        ActionDefinitionInterface $actionDefinition,
        \DateTime $achievedAt = null
    )
    {
        $this->setPlayer($player);
        $this->setActionDefinition($actionDefinition);
        $this->setAchievedAt($achievedAt ?: new \DateTime());
    }

    /**
     * {@inheritDoc}
     */
    public function getAchievedAt(): \DateTime
    {
        return $this->achievedAt;
    }

    /**
     * {@inheritDoc}
     */
    public function getActionDefinition(): ActionDefinitionInterface
    {
        return $this->actionDefinition;
    }

    /**
     * {@inheritDoc}
     */
    public function setAchievedAt(\DateTime $achievedAt)
    {
        $this->achievedAt = $achievedAt;
    }

    /**
     * {@inheritDoc}
     */
    public function setActionDefinition(ActionDefinitionInterface $actionDefinition)
    {
        $this->actionDefinition = $actionDefinition;
    }

    /**
     * {@inheritDoc}
     */
    public function setPlayer(PlayerInterface $player)
    {
        $this->player = $player;
    }

    /**
     * {@inheritDoc}
     */
    public function getPlayer(): PlayerInterface
    {
        return $this->player;
    }

    /**
     * {@inheritDoc}
     */
    public function __toString(): string
    {
        return $this->getActionDefinition()->getName();
    }
}
