<?php

namespace Yay\Component\Engine\AchievementValidator;

use Yay\Component\Entity\Achievement\AchievementDefinitionInterface;
use Yay\Component\Entity\Achievement\PersonalActionCollection;
use Yay\Component\Entity\Achievement\PersonalActionInterface;
use Yay\Component\Entity\PlayerInterface;

class ValidationHelper
{
    public function getPersonalActions(PlayerInterface $player): PersonalActionCollection
    {
        $collection = $player->getPersonalActions();
        if (!$collection instanceof PersonalActionCollection) {
            $collection =  new PersonalActionCollection($collection->toArray());
        }

        return $collection;
    }

    public function getPersonalActionsByAchievement(
        PlayerInterface $player,
        AchievementDefinitionInterface $achievementDefinition
    ): PersonalActionCollection {
        return $this->getPersonalActions($player)
            ->filter(function (PersonalActionInterface $personalAction) use ($achievementDefinition) {
                return $this->filterByMatchingActionDefinitions($personalAction, $achievementDefinition);
            })
            ->filter(function (PersonalActionInterface $personalAction) use ($achievementDefinition) {
                return $this->filterByAchievementCreatedAt($personalAction, $achievementDefinition);
            });
    }

    public function filterByMatchingActionDefinitions(
        PersonalActionInterface $personalAction,
        $achievementDefinition
    ): bool {
        return in_array(
            $personalAction->getActionDefinition(),
            $achievementDefinition->getActionDefinitions()->toArray()
        );
    }

    public function filterByAchievementCreatedAt(
        PersonalActionInterface $personalAction,
        $achievementDefinition
    ): bool {
        return $personalAction->getAchievedAt() >= $achievementDefinition->getCreatedAt();
    }
}
