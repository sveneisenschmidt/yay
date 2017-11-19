<?php

namespace Component\Entity\Achievement;

use Doctrine\Common\Collections\Collection as CollectionInterface;

interface AchievementDefinitionInterface
{
    public function getName(): string;

    public function getActionDefinitions(): CollectionInterface;

    public function getCreatedAt(): \DateTime;

    public function getLabel(): string;

    public function getDescription(): string;

    public function getPoints(): int;
}
