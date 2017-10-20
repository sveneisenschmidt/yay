<?php

namespace Yay\Component\Entity\Achievement;

use Doctrine\Common\Collections\Collection as CollectionInterface;

class AchievementDefinition implements AchievementDefinitionInterface
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $label = '';

    /**
     * @var int
     */
    protected $points;

    /**
     * @var string
     */
    protected $description = '';

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
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * {@inheritdoc}
     */
    public function getActionDefinitions(): CollectionInterface
    {
        return $this->actionDefinitions;
    }

    /**
     * {@inheritdoc}
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
        if (!$this->hasActionDefinition($actionDefinition)) {
            $this->actionDefinitions->add($actionDefinition);
        }
    }

    /**
     * @param ActionDefinitionInterface $actionDefinition
     *
     * @return bool
     */
    public function hasActionDefinition(ActionDefinitionInterface $actionDefinition)
    {
        $callback = function ($index, ActionDefinitionInterface $item) use ($actionDefinition) {
            return $item->getName() == $actionDefinition->getName();
        };

        return $this->actionDefinitions->exists($callback);
    }

    /**
     * {@inheritdoc}
     */
    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * @param string $label
     */
    public function setLabel(string $label)
    {
        $this->label = $label;
    }

    /**
     * {@inheritdoc}
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description)
    {
        $this->description = $description;
    }

    /**
     * {@inheritdoc}
     */
    public function getPoints(): int
    {
        return (int) $this->points;
    }

    /**
     * Set the {@see $points} property.
     *
     * @param int $points
     */
    public function setPoints(string $points)
    {
        $this->points = $points;
    }
}
