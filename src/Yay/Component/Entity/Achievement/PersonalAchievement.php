<?php

namespace Yay\Component\Entity\Achievement;

use Yay\Component\Entity\Achievement\PersonalActionInterface;
use Yay\Component\Entity\Achievement\AchievementDefinition;
use Yay\Component\Entity\Achievement\AchievementDefinitionInterface;
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
     * @var AchievementDefinitionInterface
     */
    protected $achievementDefinition;

    /**
     * @var PlayerInterface
     */
    protected $player;

    /**
     * AchievementPersonalAction constructor.
     *
     * @param PlayerInterface         $user
     * @param AchievementDefinitionInterface $achievementDefinition
     * @param \DateTime|null          $achievedAt
     */
    public function __construct(
        PlayerInterface $player,
        AchievementDefinitionInterface $achievementDefinition,
        \DateTime $achievedAt = null
    )
    {
        $this->setPlayer($player);
        $this->setAchievementDefinition($achievementDefinition);
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
    public function getAchievementDefinition(): AchievementDefinitionInterface
    {
        return $this->achievementDefinition;
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
    public function setAchievementDefinition(AchievementDefinitionInterface $achievementDefinition)
    {
        $this->achievementDefinition = $achievementDefinition;
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
        return $this->getAchievementDefinition()->getName();
    }
}
