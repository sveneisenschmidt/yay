<?php

namespace Yay\Component\Engine;

use Doctrine\Common\Collections\Collection as CollectionInterface;

use Yay\Component\Engine\StorageInterface;
use Yay\Component\Entity\Achievement\ActionDefinitionCollection;
use Yay\Component\Entity\Achievement\ActionDefinitionInterface;
use Yay\Component\Entity\Achievement\AchievementDefinitionCollection;
use Yay\Component\Entity\Achievement\AchievementDefinitionInterface;
use Yay\Component\Entity\Achievement\PersonalAchievementInterface;
use Yay\Component\Entity\Achievement\PersonalActionInterface;
use Yay\Component\Entity\Player;
use Yay\Component\Entity\PlayerCollection;
use Yay\Component\Entity\PlayerInterface;

trait StorageTrait
{
    /**
     * @var StorageInterface
     */
    protected $storage;

    /**
     * @param StorageInterface $storage
     */
    public function setStorage(StorageInterface $storage)
    {
        $this->storage = $storage;
    }

    /**
     * @return StorageInterface
     */
    public function getStorage(): StorageInterface
    {
        return $this->storage;
    }

    /**
     * @param int $id
     *
     * @return null|PlayerInterface
     */
    public function findPlayer(int $id)
    {
        return $this->getStorage()->findPlayer($id);
    }

    /**
     * @param array $criteria
     *
     * @return PlayerCollection
     */
    public function findPlayerBy(array $criteria = []): PlayerCollection
    {
        return $this->getStorage()->findPlayerBy($criteria);
    }

    /**
     *
     * @return PlayerCollection
     */
    public function findPlayerAny(): PlayerCollection
    {
        return $this->findPlayerBy([]);
    }

    /**
     * @param array $criteria
     *
     * @return AchievementDefinitionCollection
     */
    public function findAchievementDefinitionBy(array $criteria = []): AchievementDefinitionCollection
    {
        return $this->getStorage()->findAchievementDefinitionBy($criteria);
    }

    /**
     *
     * @return AchievementDefinitionCollection
     */
    public function findAchievementDefinitionAny(): AchievementDefinitionCollection
    {
        return $this->findAchievementDefinitionBy([]);
    }

    /**
     * @param array $criteria
     *
     * @return ActionDefinitionCollection
     */
    public function findActionDefinitionBy(array $criteria = []): ActionDefinitionCollection
    {
        return $this->getStorage()->findActionDefinitionBy($criteria);
    }

    /**
     *
     * @return ActionDefinitionCollection
     */
    public function findActionDefinitionAny(): ActionDefinitionCollection
    {
        return $this->findActionDefinitionBy([]);
    }

    /**
     * @param PersonalActionInterface $personalAction
     */
    public function savePersonalAction(PersonalActionInterface $personalAction)
    {
        $this->getStorage()->savePersonalAction($personalAction);
    }

    /**
     * @param PersonalAchievementInterface $personalAchievement
     */
    public function savePersonalAchievement(PersonalAchievementInterface $personalAchievement)
    {
        $this->getStorage()->savePersonalAchievement($personalAchievement);
    }

    /**
     * @param PlayerInterface $player
     */
    public function refreshPlayer(PlayerInterface $player)
    {
        $this->getStorage()->refreshPlayer($player);
    }
}
