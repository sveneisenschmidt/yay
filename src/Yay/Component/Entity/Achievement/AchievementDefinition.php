<?php

namespace Yay\Component\Entity\Achievement;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection as CollectionInterface;

use Yay\Component\Entity\Achievement\ActionDefinition;
use Yay\Component\Entity\Achievement\ActionDefinitionInterface;
use Yay\Component\Entity\Achievement\AchievementDefinitionInterface;
use Yay\Component\Entity\Achievement\PersonalActionInterface;
use Yay\Component\Entity\Player;
use Yay\Component\Entity\PlayerInterface;

class AchievementDefinition implements AchievementDefinitionInterface
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var \DateTime
     */
    protected $createdAt;

    /**
     * @var array|ActionDefinitionInterface[]
     */
    protected $actionDefinitions;

    /**
     * @var array|PersonalAchievementInterface[]
     */
    protected $personalAchievements;

    /**
     * AchievementDefinition constructor.
     *
     * @param string    $name
     * @param \DateTime $createdAt
     */
    public function __construct(string $name, \DateTime $createdAt = null)
    {
        $this->name = $name;
        $this->createdAt = ($createdAt ?: new \DateTime());
        $this->actionDefinitions = new ActionDefinitionCollection();
    }

    /**
     * {@inheritDoc}
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * {@inheritDoc}
     */
    public function getActionDefinitions(): CollectionInterface
    {
        return $this->actionDefinitions;
    }

    /**
     * {@inheritDoc}
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    /**
     * @param ActionDefinitionInterface $actionDefinition
     */
    public function addActionDefinition(ActionDefinitionInterface $actionDefinition)
    {
        $this->actionDefinitions->add($actionDefinition);
    }
}
