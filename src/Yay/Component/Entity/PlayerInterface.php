<?php

namespace Yay\Component\Entity;

use Doctrine\Common\Collections\Collection as CollectionInterface;

use Yay\Component\Entity\Achievement\GoalDefinitionInterface;

interface PlayerInterface
{
    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @param string $username
     *
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
     *
     * @return CollectionInterface
     */
    public function getSteps(): CollectionInterface;

    /**
     *
     * @return CollectionInterface
     */
    public function getPersonalAchievements(): CollectionInterface;

    /**
     *
     * @return bool
     */
    public function hasPersonalAchievement(GoalDefinitionInterface $goalDefinition): bool;
}