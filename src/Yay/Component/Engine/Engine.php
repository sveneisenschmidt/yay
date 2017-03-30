<?php

namespace Yay\Component\Engine;

use Doctrine\Common\Collections\Collection as CollectionInterface;

use Yay\Component\Entity\Achievement\ActionDefinition;
use Yay\Component\Entity\Achievement\ActionDefinitionCollection;
use Yay\Component\Entity\Achievement\GoalDefinition;
use Yay\Component\Entity\Achievement\GoalDefinitionCollection;
use Yay\Component\Entity\Achievement\PersonalAchievement;
use Yay\Component\Entity\Achievement\PersonalAchievementInterface;
use Yay\Component\Entity\Achievement\Step;
use Yay\Component\Entity\Achievement\StepCollection;
use Yay\Component\Entity\Achievement\StepInterface;
use Yay\Component\Entity\Player;
use Yay\Component\Entity\PlayerInterface;
use Yay\Component\Engine\GoalValidatorCollection;
use Yay\Component\Engine\GoalValidatorInterface;
use Yay\Component\Engine\StorageTrait;

class Engine
{
    use StorageTrait;

    /**
     * @var GoalValidatorCollection
     */
    protected $goalValidatorCollection;

    /**
     * Engine constructor.
     *
     * @param StorageInterface $storage
     * @param array|null       $goalValidators
     */
    public function __construct(StorageInterface $storage, GoalValidatorCollection $goalValidatorCollection = null)
    {
        $this->setStorage($storage);
        $this->goalValidatorCollection = $goalValidatorCollection ?: new GoalValidatorCollection();
    }

    /**
     * @return GoalValidatorCollection
     */
    public function getGoalValidators()
    {
        return $this->goalValidatorCollection;
    }

    /**
     * Collects Step(s) from a Player, ensures that we always get a StepCollection
     *
     * @param PlayerInterface $player
     *
     * @return StepCollection
     */
    public function getPlayerSteps(PlayerInterface $player): StepCollection
    {
        $stepCollection = $player->getSteps();
        if ($stepCollection instanceof StepCollection) {
            return $stepCollection;
        }

        return new StepCollection($stepCollection->toArray());
    }

    /**
     * Collects ActionDefinition(s) from Step(s)
     *
     * @param StepCollection $stepCollection
     *
     * @return ActionDefinitionCollection
     */
    public function extractActionDefinitions(StepCollection $stepCollection): ActionDefinitionCollection
    {
        $actionDefinitionCollection = new ActionDefinitionCollection();

        foreach($stepCollection as $step) {
            $actionDefinition = $step->getActionDefinition();
            if (!$actionDefinitionCollection->contains($actionDefinition)) {
                $actionDefinitionCollection->add($actionDefinition);
            }
        }


        return $actionDefinitionCollection;
    }

    /**
     * Gets GoalDefinition(s) by ActionDefinition(s)
     *
     * @param ActionDefinitionCollection $actionDefinitionCollection
     *
     * @return GoalDefinitionCollection
     */
    public function getMatchingGoalDefinitions(
        ActionDefinitionCollection $actionDefinitionCollection
    ): GoalDefinitionCollection
    {
        /** @var array|GoalDefinition[] $goalDefinitions */
        $goalDefinitions = $this->getStorage()->findGoalDefinitionBy([]);
        $goalDefinitionCollection = new GoalDefinitionCollection();

        foreach ($goalDefinitions as $index => $goalDefinition) {
            $intersection = array_intersect(
                $goalDefinition->getActionDefinitions()->toArray(),
                $actionDefinitionCollection->toArray()
            );

            if (count($intersection) > 0) {
                $goalDefinitionCollection->add($goalDefinition);
            }
        }

        return $goalDefinitionCollection;
    }

    /**
     * @param PlayerInterface $player
     * @param StepCollection $collection
     */
    public function advance(PlayerInterface $player, StepCollection $collection = null): array
    {
        if ($collection) {
            $this->collect($collection);
        }

        return $this->calculate($player);
    }

    /**
     * @param StepCollection $collection
     */
    public function collect(StepCollection $collection)
    {
        // Collect players to refresh them later
        $players = [];
        foreach($collection as $step) {
            if (!in_array($step->getPlayer(), $players)) {
                $players[] = $step->getPlayer();
            }
        }

        // Persist new steps to database
        /** @var StepInterface $step */
        foreach($collection as $step) {
            $this->saveStep($step);
        }

        // Refresh players
        foreach ($players as $player) {
            $this->refreshPlayer($player);
        }
    }

    /**
     * @param PlayerInterface $player
     *
     * @return array|PersonalAchievementInterface[]
     */
    public function calculate(PlayerInterface $player)
    {
        $stepCollection = $this->getPlayerSteps($player);
        $actionDefinitionCollection = $this->extractActionDefinitions($stepCollection);
        $goalDefinitionCollection = $this->getMatchingGoalDefinitions($actionDefinitionCollection);

        if ($goalDefinitionCollection->count() < 1) {
            return [];
        }

        if ($this->getGoalValidators()->count() < 1) {
            return [];
        }

        $personalAchievements = [];
        foreach($this->getGoalValidators() as $goalValidator) {
            foreach ($goalDefinitionCollection as $goalDefinition) {
                if (!$goalValidator->supports($goalDefinition)) {
                    continue;
                }
                if ($player->hasPersonalAchievement($goalDefinition)) {
                    continue;
                }
                if ($goalValidator->validate($player, $goalDefinition, $stepCollection)) {
                    $personalAchievement = new PersonalAchievement($player, $goalDefinition);
                    $personalAchievements []= $personalAchievement;

                    $this->savePersonalAchievement($personalAchievement);
                    $this->refreshPlayer($player);
                }
            }
        }

        return $personalAchievements;
    }
}
