<?php

namespace Component\Entity;

use Doctrine\Common\Collections\Collection as CollectionInterface;
use Component\Entity\Achievement\AchievementDefinitionInterface;

interface PlayerInterface
{
    public function setCreatedAt(\DateTime $createdAt): void;

    public function getCreatedAt(): \DateTime;

    public function getName(): string;

    public function getUsername(): string;

    public function getEmail(): string;

    public function setName(string $name): void;

    public function setUsername(string $username): void;

    public function setEmail(string $email): void;

    public function getPersonalActions(): CollectionInterface;

    public function getPersonalAchievements(): CollectionInterface;

    public function hasPersonalAchievement(AchievementDefinitionInterface $achievementDefinition): bool;

    public function getScore(): int;

    public function refreshScore(): int;

    public function getImageUrl(): string;
}
