<?php

namespace Yay\Component\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection as CollectionInterface;
use Yay\Component\Entity\Achievement\AchievementDefinitionInterface;
use Yay\Component\Entity\Achievement\PersonalAchievementInterface;
use Yay\Component\Entity\Achievement\PersonalActionCollection;

class Player implements PlayerInterface
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $username;

    /**
     * @var string
     */
    protected $email;

    /**
     * @var string
     */
    protected $imageUrl;

    /**
     * @var PersonalActionCollection
     */
    protected $personalActions;

    /**
     * @var array|PersonalAchievementInterface[]
     */
    protected $personalAchievements;

    /**
     * @var int
     */
    protected $score = 0;

    /**
     * Player constructor.
     */
    public function __construct()
    {
        $this->personalActions = new ArrayCollection();
        $this->personalAchievements = new ArrayCollection();
    }

    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * {@inheritdoc}
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * {@inheritdoc}
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * {@inheritdoc}
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * {@inheritdoc}
     */
    public function setUsername(string $username)
    {
        $this->username = $username;
    }

    /**
     * {@inheritdoc}
     */
    public function setEmail(string $email)
    {
        $this->email = $email;
    }

    /**
     * {@inheritdoc}
     */
    public function getPersonalActions(): CollectionInterface
    {
        return $this->personalActions;
    }

    /**
     * {@inheritdoc}
     */
    public function getPersonalAchievements(): CollectionInterface
    {
        return $this->personalAchievements;
    }

    /**
     * @param AchievementDefinitionInterface $achievementDefinition
     *
     * @return bool
     */
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

    /**
     * {@inheritdoc}
     */
    public function getScore(): int
    {
        return $this->score;
    }

    /**
     * @param int $score
     */
    public function setScore(int $score)
    {
        $this->score = $score;
    }

    /**
     * {@inheritdoc}
     */
    public function refreshScore(): int
    {
        $score = 0;
        foreach ($this->getPersonalAchievements() as $personalAchievement) {
            $score += $personalAchievement->getAchievementDefinition()->getPoints();
        }

        $this->setScore($score);

        return $this->getScore();
    }

    /**
     * {@inheritdoc}
     */
    public function getImageUrl(): string
    {
        return $this->imageUrl;
    }

    /**
     * @param string $imageUrl
     */
    public function setImageUrl(string $imageUrl)
    {
        $this->imageUrl = $imageUrl;
    }
}
