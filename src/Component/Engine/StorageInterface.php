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

interface StorageInterface
{
    /**
     * @param int $id
     *
     * @return PlayerInterface|null
     */
    public function findPlayer(int $id): ?PlayerInterface;

    /**
     * @param array $criteria
     *
     * @return PlayerCollection
     */
    public function findPlayerBy(array $criteria = []): PlayerCollection;

    /**
     * @param PlayerInterface $player
     */
    public function refreshPlayer(PlayerInterface $player);

    /**
     * @param PlayerInterface $player
     */
    public function savePlayer(PlayerInterface $player);

    /**
     * @param string $name
     *
     * @return AchievementDefinitionCollection
     */
    public function findAchievementDefinition(string $name);

    /**
     * @param array $criteria
     *
     * @return AchievementDefinitionCollection
     */
    public function findAchievementDefinitionBy(array $criteria = []): AchievementDefinitionCollection;

    /**
     * @param AchievementDefinitionInterface $achievementDefinition
     */
    public function saveAchievementDefinition(AchievementDefinitionInterface $achievementDefinition);

    /**
     * @param string $name
     *
     * @return ActionDefinitionInterface|null
     */
    public function findActionDefinition(string $name): ?ActionDefinitionInterface;

    /**
     * @param array $criteria
     *
     * @return ActionDefinitionCollection
     */
    public function findActionDefinitionBy(array $criteria = []): ActionDefinitionCollection;

    /**
     * @param ActionDefinitionInterface $actionDefinition
     */
    public function saveActionDefinition(ActionDefinitionInterface $actionDefinition);

    /**
     * @param PersonalActionInterface $personalAction
     */
    public function savePersonalAction(PersonalActionInterface $personalAction);

    /**
     * @param PersonalAchievementInterface $personalAchievement
     */
    public function savePersonalAchievement(PersonalAchievementInterface $personalAchievement);

    /**
     * @param string $name
     *
     * @return LevelInterface|null
     */
    public function findLevel(string $name): ?LevelInterface;

    /**
     * @param array $criteria
     *
     * @return LevelCollection
     */
    public function findLevelBy(): LevelCollection;

    /**
     * @param LevelInterface $level
     */
    public function saveLevel(LevelInterface $level);
}
