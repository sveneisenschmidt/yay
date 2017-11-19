<?php

namespace Component\Entity\Achievement;

use Doctrine\Common\Collections\Collection as CollectionInterface;

interface AchievementDefinitionInterface
{
    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @return CollectionInterface
     */
    public function getActionDefinitions(): CollectionInterface;

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime;

    /**
     * @return string
     */
    public function getLabel(): string;

    /**
     * @return string
     */
    public function getDescription(): string;

    /**
     * @return int
     */
    public function getPoints(): int;
}
