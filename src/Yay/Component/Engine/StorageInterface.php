<?php

namespace Yay\Component\Engine;

use Doctrine\Common\Collections\Collection as CollectionInterface;

use Yay\Component\Entity\Achievement\ActionDefinitionCollection;
use Yay\Component\Entity\Achievement\ActionDefinitionInterface;
use Yay\Component\Entity\Achievement\AchievementDefinitionCollection;
use Yay\Component\Entity\Achievement\AchievementDefinitionInterface;
use Yay\Component\Entity\Achievement\PersonalAchievementInterface;
use Yay\Component\Entity\Achievement\PersonalActionInterface;
use Yay\Component\Entity\Player;
use Yay\Component\Entity\PlayerCollection;
use Yay\Component\Entity\PlayerInterface;

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
     * @param int $id
     *
     * @return AchievementDefinitionCollection
     */
    public function findAchievementDefinition(int $id);

    /**
     * @param array $criteria
     *
     * @return AchievementDefinitionCollection
     */
    public function findAchievementDefinitionBy(array $criteria = []): AchievementDefinitionCollection;

    /**
     * @param int $id
     *
     * @return ActionDefinitionInterface|null
     */
    public function findActionDefinition(int $id): ?ActionDefinitionInterface;

    /**
     * @param array $criteria
     *
     * @return ActionDefinitionCollection
     */
    public function findActionDefinitionBy(array $criteria = []): ActionDefinitionCollection;

    /**
     * @param PersonalActionInterface $personalAction
     */
    public function savePersonalAction(PersonalActionInterface $personalAction);

    /**
     * @param PersonalAchievementInterface $personalAchievement
     */
    public function savePersonalAchievement(PersonalAchievementInterface $personalAchievement);
}
