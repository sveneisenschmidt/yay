<?php

namespace Component\Entity\Achievement;

use Component\Entity\PlayerInterface;

class PersonalAchievement implements PersonalAchievementInterface
{
    /** @var int */
    protected $id;

    /** @var \DateTime */
    protected $achievedAt;

    /** @var AchievementDefinitionInterface */
    protected $achievementDefinition;

    /** @var PlayerInterface */
    protected $player;

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

    public function getAchievedAt(): \DateTime
    {
        return $this->achievedAt;
    }

    public function getAchievementDefinition(): AchievementDefinitionInterface
    {
        return $this->achievementDefinition;
    }

    public function setAchievedAt(\DateTime $achievedAt): void
    {
        $this->achievedAt = $achievedAt;
    }

    public function setAchievementDefinition(AchievementDefinitionInterface $achievementDefinition): void
    {
        $this->achievementDefinition = $achievementDefinition;
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
        return $this->getAchievementDefinition()->getName();
    }
}
