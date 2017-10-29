<?php

namespace Yay\Component\Engine;

use Yay\Component\Entity\Achievement\ActionDefinition;
use Yay\Component\Entity\Achievement\ActionDefinitionCollection;
use Yay\Component\Entity\Achievement\AchievementDefinition;
use Yay\Component\Entity\Achievement\AchievementDefinitionCollection;
use Yay\Component\Entity\Achievement\PersonalAchievement;
use Yay\Component\Entity\Achievement\PersonalAction;
use Yay\Component\Entity\Achievement\PersonalActionCollection;
use Yay\Component\Entity\Achievement\PersonalActionInterface;
use Yay\Component\Entity\Player;
use Yay\Component\Entity\PlayerInterface;

class Engine
{
    use StorageTrait;

    /**
     * @var AchievementValidatorCollection
     */
    protected $achievementValidatorCollection;

    /**
     * Engine constructor.
     *
     * @param StorageInterface $storage
     * @param array|null       $achievementValidators
     */
    public function __construct(StorageInterface $storage, AchievementValidatorCollection $achievementValidatorCollection = null)
    {
        $this->setStorage($storage);
        $this->achievementValidatorCollection = !$achievementValidatorCollection ? new AchievementValidatorCollection() : $achievementValidatorCollection;
    }

    /**
     * @return AchievementValidatorCollection
     */
    public function getAchievementValidators()
    {
        return $this->achievementValidatorCollection;
    }

    /**
     * Collects PersonalAction(s) from a Player, ensures that we always get a PersonalActionCollection.
     *
     * @param PlayerInterface $player
     *
     * @return PersonalActionCollection
     */
    public function getPlayerPersonalActions(PlayerInterface $player): PersonalActionCollection
    {
        $personalActionCollection = $player->getPersonalActions();
        if (!$personalActionCollection instanceof PersonalActionCollection) {
            return new PersonalActionCollection($personalActionCollection->toArray());
        }

        return $personalActionCollection;
    }

    /**
     * Collects ActionDefinition(s) from PersonalAction(s).
     *
     * @param PersonalActionCollection $personalActionCollection
     *
     * @return ActionDefinitionCollection
     */
    public function extractActionDefinitions(PersonalActionCollection $personalActionCollection): ActionDefinitionCollection
    {
        $actionDefinitionCollection = new ActionDefinitionCollection();

        foreach ($personalActionCollection as $personalAction) {
            $actionDefinition = $personalAction->getActionDefinition();
            if (!$actionDefinitionCollection->contains($actionDefinition)) {
                $actionDefinitionCollection->add($actionDefinition);
            }
        }

        return $actionDefinitionCollection;
    }

    /**
     * Gets AchievementDefinition(s) by ActionDefinition(s).
     *
     * @param ActionDefinitionCollection $actionDefinitionCollection
     *
     * @return AchievementDefinitionCollection
     */
    public function extractMatchingAchievementDefinitions(
        ActionDefinitionCollection $actionDefinitionCollection,
        AchievementDefinitionCollection $achievementDefinitionCollection
    ): AchievementDefinitionCollection {
        $matchingAchievementDefinitionCollection = new AchievementDefinitionCollection();
        foreach ($achievementDefinitionCollection as $achievementDefinition) {
            $intersection = array_intersect(
                $achievementDefinition->getActionDefinitions()->toArray(),
                $actionDefinitionCollection->toArray()
            );

            if (count($intersection) > 0) {
                $matchingAchievementDefinitionCollection->add($achievementDefinition);
            }
        }

        return $matchingAchievementDefinitionCollection;
    }

    /**
     * @param PlayerInterface          $player
     * @param PersonalActionCollection $collection
     */
    public function advance(PlayerInterface $player, PersonalActionCollection $collection = null): array
    {
        if ($collection) {
            $this->collectPersonalActions($collection);
        }

        $achievementValidatorCollection = $this->getAchievementValidators();
        $personalActionCollection = $this->getPlayerPersonalActions($player);
        $actionDefinitionCollection = $this->extractActionDefinitions($personalActionCollection);
        $achievementDefinitionCollection = $this->extractMatchingAchievementDefinitions(
            $actionDefinitionCollection,
            $this->getStorage()->findAchievementDefinitionBy([])
        );

        if ($achievementDefinitionCollection->count() < 1) {
            return [];
        }

        if ($achievementValidatorCollection->count() < 1) {
            return [];
        }

        $personalAchievements = [];
        foreach ($achievementValidatorCollection as $achievementValidator) {
            foreach ($achievementDefinitionCollection as $achievementDefinition) {
                if ($player->hasPersonalAchievement($achievementDefinition)) {
                    continue;
                }

                if (!$achievementValidator->supports($achievementDefinition)) {
                    continue;
                }

                if ($achievementValidator->validate($player, $achievementDefinition, $personalActionCollection)) {
                    $personalAchievement = new PersonalAchievement($player, $achievementDefinition);
                    $personalAchievements[] = $personalAchievement;

                    $this->savePersonalAchievement($personalAchievement);
                    $this->refreshPlayer($player);
                }
            }
        }

        $player->refreshScore();
        $this->savePlayer($player);

        return $personalAchievements;
    }

    /**
     * @param PersonalActionCollection $collection
     */
    public function collectPersonalActions(PersonalActionCollection $collection)
    {
        // Collect players to refresh them later
        $players = [];
        foreach ($collection as $personalAction) {
            if (!in_array($personalAction->getPlayer(), $players)) {
                $players[] = $personalAction->getPlayer();
            }
        }

        // Persist new personalActions to database
        foreach ($collection as $personalAction) {
            $this->savePersonalAction($personalAction);
        }

        // Refresh players
        foreach ($players as $player) {
            $this->refreshPlayer($player);
        }
    }
}
