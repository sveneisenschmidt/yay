<?php

namespace Component\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection as CollectionInterface;
use Component\Entity\Achievement\AchievementDefinitionInterface;
use Component\Entity\Achievement\PersonalAchievementInterface;
use Component\Entity\Achievement\PersonalActionCollection;

class Player implements PlayerInterface
{
    /** @var int */
    protected $id;

    /** @var \DateTime */
    protected $createdAt;

    /** @var string */
    protected $name;

    /** @var string */
    protected $username;

    /** @var string */
    protected $email;

    /** @var string */
    protected $imageUrl;

    /** @var PersonalActionCollection */
    protected $personalActions;

    /**
     * @var array<PersonalAchievementInterface>
     */
    protected $personalAchievements;

    /**
     * @var ActivityCollection
     */
    protected $activities;

    /** @var int */
    protected $score = 0;

    /** @var int */
    protected $level = 0;

    public function __construct(\DateTime $createdAt = null)
    {
        $this->personalActions = new ArrayCollection();
        $this->personalAchievements = new ArrayCollection();
        $this->activities = new ArrayCollection();
        $this->setCreatedAt($createdAt ?: new \DateTime());
    }

    public function setCreatedAt(\DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getPersonalActions(): CollectionInterface
    {
        return $this->personalActions;
    }

    public function getPersonalAchievements(): CollectionInterface
    {
        return $this->personalAchievements;
    }

    public function getActivities(): CollectionInterface
    {
        return $this->activities;
    }

    public function hasPersonalAchievement(AchievementDefinitionInterface $achievementDefinition): bool
    {
        /** @var PersonalAchievementInterface $personalAchievement */
        foreach ($this->getPersonalAchievements() as $personalAchievement) {
            if ($personalAchievement->getAchievementDefinition()->getName() == $achievementDefinition->getName()) {
                return true;
            }
        }

        return false;
    }

    public function getScore(): int
    {
        return $this->score;
    }

    public function setScore(int $score): void
    {
        $this->score = $score;
    }

    public function getLevel(): int
    {
        return $this->level;
    }

    public function setLevel(int $level): void
    {
        $this->level = $level;
    }

    public function getImageUrl(): string
    {
        return $this->imageUrl;
    }

    public function setImageUrl(string $imageUrl): void
    {
        $this->imageUrl = $imageUrl;
    }
}
