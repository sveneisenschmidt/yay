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
    /* @var StorageInterface */
    protected $storage;

    public function setStorage(StorageInterface $storage): void
    {
        $this->storage = $storage;
    }

    public function getStorage(): StorageInterface
    {
        return $this->storage;
    }

    public function findPlayer(int $id): ?PlayerInterface
    {
        return $this->getStorage()->findPlayer($id);
    }

    public function savePlayer(PlayerInterface $player): void
    {
        $this->getStorage()->savePlayer($player);
    }

    public function findPlayerBy(array $criteria = []): PlayerCollection
    {
        return $this->getStorage()->findPlayerBy($criteria);
    }

    public function findPlayerAny(): PlayerCollection
    {
        return $this->findPlayerBy([]);
    }

    public function findAchievementDefinitionBy(array $criteria = []): AchievementDefinitionCollection
    {
        return $this->getStorage()->findAchievementDefinitionBy($criteria);
    }

    public function findAchievementDefinitionAny(): AchievementDefinitionCollection
    {
        return $this->findAchievementDefinitionBy([]);
    }

    public function saveAchievementDefinition(AchievementDefinitionInterface $achievementDefinition): void
    {
        $this->getStorage()->saveAchievementDefinition($achievementDefinition);
    }

    public function findActionDefinitionBy(array $criteria = []): ActionDefinitionCollection
    {
        return $this->getStorage()->findActionDefinitionBy($criteria);
    }

    public function findActionDefinitionAny(): ActionDefinitionCollection
    {
        return $this->findActionDefinitionBy([]);
    }

    public function saveActionDefinition(ActionDefinitionInterface $actionDefinition): void
    {
        $this->getStorage()->saveActionDefinition($actionDefinition);
    }

    public function savePersonalAction(PersonalActionInterface $personalAction): void
    {
        $this->getStorage()->savePersonalAction($personalAction);
    }

    public function savePersonalAchievement(PersonalAchievementInterface $personalAchievement): void
    {
        $this->getStorage()->savePersonalAchievement($personalAchievement);
    }

    public function refreshPlayer(PlayerInterface $player): void
    {
        $this->getStorage()->refreshPlayer($player);
    }

    public function findLevel(string $name): ?LevelInterface
    {
        return $this->getStorage()->findLevel($name);
    }

    public function findLevelBy(array $criteria = []): LevelCollection
    {
        return $this->getStorage()->findLevelBy($criteria);
    }

    public function findLevelAny(): LevelCollection
    {
        return $this->findLevelBy([]);
    }

    public function saveLevel(LevelInterface $level): void
    {
        $this->getStorage()->saveLevel($level);
    }
}
