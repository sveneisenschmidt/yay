<?php

namespace Component\Entity\Achievement;

use Component\Entity\PlayerInterface;

class PersonalAction implements PersonalActionInterface
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var \DateTime
     */
    protected $achievedAt;

    /**
     * @var ActionDefinitionInterface
     */
    protected $actionDefinition;

    /**
     * @var PlayerInterface
     */
    protected $player;

    /**
     * AchievementPersonalAction constructor.
     *
     * @param PlayerInterface           $user
     * @param ActionDefinitionInterface $actionDefinition
     * @param \DateTime|null            $achievedAt
     */
    public function __construct(
        PlayerInterface $player,
        ActionDefinitionInterface $actionDefinition,
        \DateTime $achievedAt = null
    ) {
        $this->setPlayer($player);
        $this->setActionDefinition($actionDefinition);
        $this->setAchievedAt($achievedAt ?: new \DateTime());
    }

    /**
     * {@inheritdoc}
     */
    public function getAchievedAt(): \DateTime
    {
        return $this->achievedAt;
    }

    /**
     * {@inheritdoc}
     */
    public function getActionDefinition(): ActionDefinitionInterface
    {
        return $this->actionDefinition;
    }

    /**
     * {@inheritdoc}
     */
    public function setAchievedAt(\DateTime $achievedAt)
    {
        $this->achievedAt = $achievedAt;
    }

    /**
     * {@inheritdoc}
     */
    public function setActionDefinition(ActionDefinitionInterface $actionDefinition)
    {
        $this->actionDefinition = $actionDefinition;
    }

    /**
     * {@inheritdoc}
     */
    public function setPlayer(PlayerInterface $player)
    {
        $this->player = $player;
    }

    /**
     * {@inheritdoc}
     */
    public function getPlayer(): PlayerInterface
    {
        return $this->player;
    }

    /**
     * {@inheritdoc}
     */
    public function __toString(): string
    {
        return $this->getActionDefinition()->getName();
    }
}
