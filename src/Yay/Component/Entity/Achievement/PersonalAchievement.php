<?php

namespace Yay\Component\Entity\Achievement;

use Yay\Component\Entity\PlayerInterface;

class PersonalAchievement implements PersonalAchievementInterface
{
    /**
     * @var int
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
     * @param PlayerInterface                $user
     * @param AchievementDefinitionInterface $achievementDefinition
     * @param \DateTime|null                 $achievedAt
     */
    public function __construct(
        PlayerInterface $player,
        AchievementDefinitionInterface $achievementDefinition,
        \DateTime $achievedAt = null
    ) {
        $this->setPlayer($player);
        $this->setAchievementDefinition($achievementDefinition);
        $this->setAchievedAt($achievedAt ?: new \DateTime());
        $player->refreshScore();
    }

    /**
     * {@inheritdoc}
     */
    public function getAchievedAt(): \DateTime
    {
        return $this->achievedAt;
    }

    /**
     * {@inheritdoc}
     */
    public function getAchievementDefinition(): AchievementDefinitionInterface
    {
        return $this->achievementDefinition;
    }

    /**
     * {@inheritdoc}
     */
    public function setAchievedAt(\DateTime $achievedAt)
    {
        $this->achievedAt = $achievedAt;
    }

    /**
     * {@inheritdoc}
     */
    public function setAchievementDefinition(AchievementDefinitionInterface $achievementDefinition)
    {
        $this->achievementDefinition = $achievementDefinition;
    }

    /**
     * {@inheritdoc}
     */
    public function setPlayer(PlayerInterface $player)
    {
        $this->player = $player;
    }

    /**
     * {@inheritdoc}
     */
    public function getPlayer(): PlayerInterface
    {
        return $this->player;
    }

    /**
     * {@inheritdoc}
     */
    public function __toString(): string
    {
        return $this->getAchievementDefinition()->getName();
    }
}
