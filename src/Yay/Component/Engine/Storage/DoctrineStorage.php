<?php

namespace Yay\Component\Engine\Storage;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Common\Collections\Collection as CollectionInterface;

use Yay\Component\Engine\StorageInterface;
use Yay\Component\Entity\Achievement\ActionDefinition;
use Yay\Component\Entity\Achievement\ActionDefinitionInterface;
use Yay\Component\Entity\Achievement\ActionDefinitionCollection;
use Yay\Component\Entity\Achievement\AchievementDefinition;
use Yay\Component\Entity\Achievement\AchievementDefinitionCollection;
use Yay\Component\Entity\Achievement\AchievementDefinitionInterface;
use Yay\Component\Entity\Achievement\PersonalAchievementInterface;
use Yay\Component\Entity\Achievement\PersonalActionInterface;
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
    public function findPlayer(int $id): ?PlayerInterface
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
    public function findAchievementDefinition(int $id): ?AchievementDefinitionInterface
    {
        return $this->manager->getRepository(AchievementDefinition::class)->find($id);
    }

    /**
     * {@inheritDoc}
     */
    public function findAchievementDefinitionBy(array $criteria = []): AchievementDefinitionCollection
    {
        $result = $this->manager->getRepository(AchievementDefinition::class)->findBy($criteria);
        return new AchievementDefinitionCollection($result);
    }

    /**
     * {@inheritDoc}
     */
    public function findActionDefinition(int $id): ?ActionDefinitionInterface
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
    public function savePlayer(PlayerInterface $player)
    {
        $this->manager->persist($player);
        $this->manager->flush();
    }

    /**
     * {@inheritDoc}
     */
    public function savePersonalAction(PersonalActionInterface $personalAction)
    {
        $this->manager->persist($personalAction);
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
