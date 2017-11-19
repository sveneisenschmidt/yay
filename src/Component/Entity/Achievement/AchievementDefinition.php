<?php

namespace Component\Entity\Achievement;

use Doctrine\Common\Collections\Collection as CollectionInterface;

class AchievementDefinition implements AchievementDefinitionInterface
{
    /* @var string */
    protected $name;

    /* @var string */
    protected $label = '';

    /* @var int */
    protected $points;

    /* @var string */
    protected $description = '';

    /* @var \DateTime */
    protected $createdAt;

    /**
     * @var array|ActionDefinitionInterface[]
     */
    protected $actionDefinitions;

    /**
     * @var array|PersonalAchievementInterface[]
     */
    protected $personalAchievements;

    public function __construct(string $name, \DateTime $createdAt = null)
    {
        $this->name = $name;
        $this->createdAt = $createdAt ? $createdAt : new \DateTime();
        $this->actionDefinitions = new ActionDefinitionCollection();
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getActionDefinitions(): CollectionInterface
    {
        return $this->actionDefinitions;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function addActionDefinition(ActionDefinitionInterface $actionDefinition)
    {
        if (!$this->hasActionDefinition($actionDefinition)) {
            $this->actionDefinitions->add($actionDefinition);
        }
    }

    public function hasActionDefinition(ActionDefinitionInterface $actionDefinition)
    {
        $callback = function ($index, ActionDefinitionInterface $item) use ($actionDefinition) {
            return $item->getName() == $actionDefinition->getName();
        };

        return $this->actionDefinitions->exists($callback);
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function setLabel(string $label): void
    {
        $this->label = $label;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getPoints(): int
    {
        return (int) $this->points;
    }

    /**
     * Set the {@see $points} property.
     */
    public function setPoints(string $points): void
    {
        $this->points = $points;
    }
}
