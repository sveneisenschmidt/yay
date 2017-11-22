<?php

namespace Component\Engine;

use Component\Engine\AchievementValidator\ValidationContext;
use Component\Entity\Achievement\ActionDefinitionCollection;
use Component\Entity\Achievement\AchievementDefinitionInterface;
use Component\Entity\Achievement\AchievementDefinitionCollection;
use Component\Entity\Achievement\PersonalAchievement;
use Component\Entity\Achievement\PersonalActionCollection;
use Component\Entity\PlayerInterface;

class Engine
{
    use StorageTrait;

    /** @var AchievementValidatorCollection */
    protected $achievementValidatorCollection;

    public function __construct(
        StorageInterface $storage,
        AchievementValidatorCollection $achievementValidatorCollection = null
    ) {
        $this->setStorage($storage);
        $this->achievementValidatorCollection = !$achievementValidatorCollection ? new AchievementValidatorCollection() : $achievementValidatorCollection;
    }

    public function getAchievementValidators(): AchievementValidatorCollection
    {
        return $this->achievementValidatorCollection;
    }

    public function getPlayerPersonalActions(
        PlayerInterface $player
    ): PersonalActionCollection {
        $personalActionCollection = $player->getPersonalActions();
        if (!$personalActionCollection instanceof PersonalActionCollection) {
            return new PersonalActionCollection($personalActionCollection->toArray());
        }

        return $personalActionCollection;
    }

    public function extractActionDefinitions(
        PersonalActionCollection $personalActionCollection
    ): ActionDefinitionCollection {
        $actionDefinitionCollection = new ActionDefinitionCollection();

        foreach ($personalActionCollection as $personalAction) {
            $actionDefinition = $personalAction->getActionDefinition();
            if (!$actionDefinitionCollection->contains($actionDefinition)) {
                $actionDefinitionCollection->add($actionDefinition);
            }
        }

        return $actionDefinitionCollection;
    }

    public function extractMatchingAchievementDefinitions(
        ActionDefinitionCollection  $actionDefinitionCollection,
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

    public function advance(
        PlayerInterface $player,
        PersonalActionCollection $collection = null
    ): array {
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
                if (!$achievementValidator->supports($achievementDefinition)) {
                    continue;
                }

                if ($player->hasPersonalAchievement($achievementDefinition) &&
                    !$achievementValidator->multiple()
                ) {
                    continue;
                }

                $validationContext = $this->createValidationContext($player, $achievementDefinition);
                if ($achievementValidator->validate($validationContext)) {
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

    public function createValidationContext(
        PlayerInterface $player,
        AchievementDefinitionInterface $achievementDefinition
    ): ValidationContext {
        return new ValidationContext($player, $achievementDefinition);
    }

    public function collectPersonalActions(PersonalActionCollection $collection): void
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
