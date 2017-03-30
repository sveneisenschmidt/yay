<?php

namespace Yay\Component\Entity\Achievement;

use Yay\Component\Entity\Achievement\StepInterface;
use Yay\Component\Entity\Achievement\GoalDefinition;
use Yay\Component\Entity\Achievement\GoalDefinitionInterface;
use Yay\Component\Entity\Player;
use Yay\Component\Entity\PlayerInterface;

class PersonalAchievement implements PersonalAchievementInterface
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
     * @var GoalDefinitionInterface
     */
    protected $goalDefinition;

    /**
     * @var PlayerInterface
     */
    protected $player;

    /**
     * AchievementStep constructor.
     *
     * @param PlayerInterface         $user
     * @param GoalDefinitionInterface $goalDefinition
     * @param \DateTime|null          $achievedAt
     */
    public function __construct(
        PlayerInterface $player,
        GoalDefinitionInterface $goalDefinition,
        \DateTime $achievedAt = null
    )
    {
        $this->setPlayer($player);
        $this->setGoalDefinition($goalDefinition);
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
    public function getGoalDefinition(): GoalDefinitionInterface
    {
        return $this->goalDefinition;
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
    public function setGoalDefinition(GoalDefinitionInterface $goalDefinition)
    {
        $this->goalDefinition = $goalDefinition;
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
        return $this->getGoalDefinition()->getName();
    }
}
