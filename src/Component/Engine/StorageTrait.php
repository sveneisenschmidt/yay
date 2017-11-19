<?php

namespace Component\Engine;

use Component\Entity\Achievement\ActionDefinitionCollection;
use Component\Entity\Achievement\ActionDefinitionInterface;
use Component\Entity\Achievement\AchievementDefinitionCollection;
use Component\Entity\Achievement\AchievementDefinitionInterface;
use Component\Entity\Achievement\PersonalAchievementInterface;
use Component\Entity\Achievement\PersonalActionInterface;
use Component\Entity\Achievement\LevelCollection;
use Component\Entity\Achievement\LevelInterface;
use Component\Entity\PlayerCollection;
use Component\Entity\PlayerInterface;

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
     * @return PlayerInterface|null
     */
    public function findPlayer(int $id): ?PlayerInterface
    {
        return $this->getStorage()->findPlayer($id);
    }

    /**
     * @param PlayerInterface $player
     */
    public function savePlayer(PlayerInterface $player)
    {
        return $this->getStorage()->savePlayer($player);
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
     * @return AchievementDefinitionCollection
     */
    public function findAchievementDefinitionAny(): AchievementDefinitionCollection
    {
        return $this->findAchievementDefinitionBy([]);
    }

    /**
     * @param AchievementDefinitionInterface $achievementDefinition
     */
    public function saveAchievementDefinition(AchievementDefinitionInterface $achievementDefinition)
    {
        $this->getStorage()->saveAchievementDefinition($achievementDefinition);
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
     * @return ActionDefinitionCollection
     */
    public function findActionDefinitionAny(): ActionDefinitionCollection
    {
        return $this->findActionDefinitionBy([]);
    }

    /**
     * @param ActionDefinitionInterface $actionDefinition
     */
    public function saveActionDefinition(ActionDefinitionInterface $actionDefinition)
    {
        $this->getStorage()->saveActionDefinition($actionDefinition);
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

    /**
     * @param string $name
     *
     * @return LevelInterface|null
     */
    public function findLevel(string $name): ?LevelInterface
    {
        return $this->getStorage()->findLevel($name);
    }

    /**
     * @param array $criteria
     *
     * @return LevelCollection
     */
    public function findLevelBy(array $criteria = []): LevelCollection
    {
        return $this->getStorage()->findLevelBy($criteria);
    }

    /**
     * @return LevelCollection
     */
    public function findLevelAny(): LevelCollection
    {
        return $this->findLevelBy([]);
    }

    /**
     * @param LevelInterface $level
     */
    public function saveLevel(LevelInterface $level)
    {
        return $this->getStorage()->saveLevel($level);
    }
}
