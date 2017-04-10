<?php

namespace Yay\Component\Entity\Achievement;

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
}
