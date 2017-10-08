<?php

namespace Yay\Component\Entity;

use Doctrine\Common\Collections\Collection as CollectionInterface;

use Yay\Component\Entity\Achievement\AchievementDefinitionInterface;

interface PlayerInterface
{
    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @return mixed
     */
    public function getUsername(): string;

    /**
     * @param string $email
     *
     * @return mixed
     */
    public function getEmail(): string;

    /**
     * @param string $name
     *
     * @return mixed
     */
    public function setName(string $name);

    /**
     * @param string $username
     *
     * @return mixed
     */
    public function setUsername(string $username);

    /**
     * @param string $email
     *
     * @return mixed
     */
    public function setEmail(string $email);

    /**
     * @return CollectionInterface
     */
    public function getPersonalActions(): CollectionInterface;

    /**
     * @return CollectionInterface
     */
    public function getPersonalAchievements(): CollectionInterface;

    /**
     * @return bool
     */
    public function hasPersonalAchievement(AchievementDefinitionInterface $achievementDefinition): bool;

    /**
     * @return int
     */
    public function getScore(): int;

    /**
     * @return int
     */
    public function refreshScore(): int;

    /**
     * @return string
     */
    public function getImageUrl(): string;
}
