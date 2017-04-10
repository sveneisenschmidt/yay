<?php

namespace Yay\Component\Engine;

use Doctrine\Common\Collections\Collection as CollectionInterface;

use Yay\Component\Entity\Achievement\ActionDefinition;
use Yay\Component\Entity\Achievement\ActionDefinitionCollection;
use Yay\Component\Entity\Achievement\AchievementDefinition;
use Yay\Component\Entity\Achievement\AchievementDefinitionCollection;
use Yay\Component\Entity\Achievement\PersonalAchievement;
use Yay\Component\Entity\Achievement\PersonalAchievementInterface;
use Yay\Component\Entity\Achievement\PersonalAction;
use Yay\Component\Entity\Achievement\PersonalActionCollection;
use Yay\Component\Entity\Achievement\PersonalActionInterface;
use Yay\Component\Entity\Player;
use Yay\Component\Entity\PlayerInterface;
use Yay\Component\Engine\AchievementValidatorCollection;
use Yay\Component\Engine\AchievementValidatorInterface;
use Yay\Component\Engine\StorageTrait;

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
        $this->achievementValidatorCollection = $achievementValidatorCollection ?: new AchievementValidatorCollection();
    }

    /**
     * @return AchievementValidatorCollection
     */
    public function getAchievementValidators()
    {
        return $this->achievementValidatorCollection;
    }

    /**
     * Collects PersonalAction(s) from a Player, ensures that we always get a PersonalActionCollection
     *
     * @param PlayerInterface $player
     *
     * @return PersonalActionCollection
     */
    public function getPlayerPersonalActions(PlayerInterface $player): PersonalActionCollection
    {
        $personalActionCollection = $player->getPersonalActions();
        if ($personalActionCollection instanceof PersonalActionCollection) {
            return $personalActionCollection;
        }

        return new PersonalActionCollection($personalActionCollection->toArray());
    }

    /**
     * Collects ActionDefinition(s) from PersonalAction(s)
     *
     * @param PersonalActionCollection $personalActionCollection
     *
     * @return ActionDefinitionCollection
     */
    public function extractActionDefinitions(PersonalActionCollection $personalActionCollection): ActionDefinitionCollection
    {
        $actionDefinitionCollection = new ActionDefinitionCollection();

        foreach($personalActionCollection as $personalAction) {
            $actionDefinition = $personalAction->getActionDefinition();
            if (!$actionDefinitionCollection->contains($actionDefinition)) {
                $actionDefinitionCollection->add($actionDefinition);
            }
        }


        return $actionDefinitionCollection;
    }

    /**
     * Gets AchievementDefinition(s) by ActionDefinition(s)
     *
     * @param ActionDefinitionCollection $actionDefinitionCollection
     *
     * @return AchievementDefinitionCollection
     */
    public function getMatchingAchievementDefinitions(
        ActionDefinitionCollection $actionDefinitionCollection
    ): AchievementDefinitionCollection
    {
        /** @var array|AchievementDefinition[] $achievementDefinitions */
        $achievementDefinitions = $this->getStorage()->findAchievementDefinitionBy([]);
        $achievementDefinitionCollection = new AchievementDefinitionCollection();

        foreach ($achievementDefinitions as $index => $achievementDefinition) {
            $intersection = array_intersect(
                $achievementDefinition->getActionDefinitions()->toArray(),
                $actionDefinitionCollection->toArray()
            );

            if (count($intersection) > 0) {
                $achievementDefinitionCollection->add($achievementDefinition);
            }
        }

        return $achievementDefinitionCollection;
    }

    /**
     * @param PlayerInterface $player
     * @param PersonalActionCollection $collection
     */
    public function advance(PlayerInterface $player, PersonalActionCollection $collection = null): array
    {
        if ($collection) {
            $this->collectPersonalActions($collection);
        }

        $personalActionCollection = $this->getPlayerPersonalActions($player);
        $actionDefinitionCollection = $this->extractActionDefinitions($personalActionCollection);
        $achievementDefinitionCollection = $this->getMatchingAchievementDefinitions($actionDefinitionCollection);

        if ($achievementDefinitionCollection->count() < 1) {
            return [];
        }

        if ($this->getAchievementValidators()->count() < 1) {
            return [];
        }

        $personalAchievements = [];
        foreach($this->getAchievementValidators() as $achievementValidator) {
            foreach ($achievementDefinitionCollection as $achievementDefinition) {
                if (!$achievementValidator->supports($achievementDefinition)) {
                    continue;
                }
                if ($player->hasPersonalAchievement($achievementDefinition)) {
                    continue;
                }
                if ($achievementValidator->validate($player, $achievementDefinition, $personalActionCollection)) {
                    $personalAchievement = new PersonalAchievement($player, $achievementDefinition);
                    $personalAchievements []= $personalAchievement;

                    $this->savePersonalAchievement($personalAchievement);
                    $this->refreshPlayer($player);
                }
            }
        }

        return $personalAchievements;
    }

    /**
     * @param PersonalActionCollection $collection
     */
    public function collectPersonalActions(PersonalActionCollection $collection)
    {
        // Collect players to refresh them later
        $players = [];
        foreach($collection as $personalAction) {
            if (!in_array($personalAction->getPlayer(), $players)) {
                $players[] = $personalAction->getPlayer();
            }
        }

        // Persist new personalActions to database
        /** @var PersonalActionInterface $personalAction */
        foreach($collection as $personalAction) {
            $this->savePersonalAction($personalAction);
        }

        // Refresh players
        foreach ($players as $player) {
            $this->refreshPlayer($player);
        }
    }
}
