<?php

namespace Yay\Component\Engine\Storage;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Common\Collections\Collection as CollectionInterface;

use Yay\Component\Engine\StorageInterface;
use Yay\Component\Entity\Achievement\ActionDefinition;
use Yay\Component\Entity\Achievement\ActionDefinitionCollection;
use Yay\Component\Entity\Achievement\GoalDefinition;
use Yay\Component\Entity\Achievement\GoalDefinitionCollection;
use Yay\Component\Entity\Achievement\GoalDefinitionInterface;
use Yay\Component\Entity\Achievement\PersonalAchievementInterface;
use Yay\Component\Entity\Achievement\StepInterface;
use Yay\Component\Entity\Player;
use Yay\Component\Entity\PlayerCollection;
use Yay\Component\Entity\PlayerInterface;

class DoctrineStorage implements StorageInterface
{
    /**
     * @var EntityManagerInterface
     */
    protected $manager;

    /**
     * DoctrineStorage constructor.
     *
     * @param EntityManagerInterface $manager
     */
    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    /**
     * {@inheritDoc}
     */
    public function findPlayer(int $id)
    {
        return $this->manager->getRepository(Player::class)->find($id);
    }

    /**
     * {@inheritDoc}
     */
    public function findPlayerBy(array $criteria = []): PlayerCollection
    {
        $result = $this->manager->getRepository(Player::class)->findBy($criteria);
        return new PlayerCollection($result);
    }

    /**
     * {@inheritDoc}
     */
    public function findGoalDefinition(int $id)
    {
        return $this->manager->getRepository(GoalDefinition::class)->find($id);
    }

    /**
     * {@inheritDoc}
     */
    public function findGoalDefinitionBy(array $criteria = []): GoalDefinitionCollection
    {
        $result = $this->manager->getRepository(GoalDefinition::class)->findBy($criteria);
        return new GoalDefinitionCollection($result);
    }

    /**
     * {@inheritDoc}
     */
    public function findActionDefinition(int $id)
    {
        return $this->manager->getRepository(ActionDefinition::class)->find($id);
    }

    /**
     * {@inheritDoc}
     */
    public function findActionDefinitionBy(array $criteria = []): ActionDefinitionCollection
    {
        $result = $this->manager->getRepository(ActionDefinition::class)->findBy($criteria);
        return new ActionDefinitionCollection($result);
    }

    /**
     * {@inheritDoc}
     */
    public function refreshPlayer(PlayerInterface $player)
    {
        $this->manager->refresh($player);
    }

    /**
     * {@inheritDoc}
     */
    public function saveStep(StepInterface $step)
    {
        $this->manager->persist($step);
        $this->manager->flush();
    }

    /**
     * {@inheritDoc}
     */
    public function savePersonalAchievement(PersonalAchievementInterface $personalAchievement)
    {
        $this->manager->persist($personalAchievement);
        $this->manager->flush();
    }
}
