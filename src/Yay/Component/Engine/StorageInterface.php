<?php

namespace Yay\Component\Engine;

use Doctrine\Common\Collections\Collection as CollectionInterface;

use Yay\Component\Entity\Achievement\ActionDefinitionCollection;
use Yay\Component\Entity\Achievement\ActionDefinitionInterface;
use Yay\Component\Entity\Achievement\GoalDefinitionCollection;
use Yay\Component\Entity\Achievement\GoalDefinitionInterface;
use Yay\Component\Entity\Achievement\PersonalAchievementInterface;
use Yay\Component\Entity\Achievement\StepInterface;
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
    public function findPlayer(int $id);

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
     * @param int $id
     *
     * @return GoalDefinitionCollection
     */
    public function findGoalDefinition(int $id);

    /**
     * @param array $criteria
     *
     * @return GoalDefinitionCollection
     */
    public function findGoalDefinitionBy(array $criteria = []): GoalDefinitionCollection;

    /**
     * @param int $id
     *
     * @return ActionDefinitionInterface|null
     */
    public function findActionDefinition(int $id);

    /**
     * @param array $criteria
     *
     * @return ActionDefinitionCollection
     */
    public function findActionDefinitionBy(array $criteria = []): ActionDefinitionCollection;

    /**
     * @param StepInterface $step
     */
    public function saveStep(StepInterface $step);

    /**
     * @param PersonalAchievementInterface $personalAchievement
     */
    public function savePersonalAchievement(PersonalAchievementInterface $personalAchievement);
}
