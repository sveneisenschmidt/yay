<?php

namespace Yay\Component\Engine\AchievementValidator;

use Yay\Component\Entity\Achievement\AchievementDefinitionInterface;
use Yay\Component\Entity\Achievement\PersonalActionCollection;
use Yay\Component\Entity\Achievement\PersonalActionInterface;
use Yay\Component\Entity\PlayerInterface;

class ValidationContext
{
    /* @var AchievementDefinitionInterface */
    protected $achievementDefinition;

    /* @var PlayerInterface */
    protected $player;

    public function __construct(
        PlayerInterface $player,
        AchievementDefinitionInterface $achievementDefinition
    ) {
        $this->player = $player;
        $this->achievementDefinition = $achievementDefinition;
    }

    public function getPlayer(): PlayerInterface
    {
        return $this->player;
    }

    public function getAchievementDefinition(): AchievementDefinitionInterface
    {
        return $this->achievementDefinition;
    }

    public function getPersonalActions(): PersonalActionCollection
    {
        $collection = $this->getPlayer()->getPersonalActions();
        if (!$collection instanceof PersonalActionCollection) {
            $collection = new PersonalActionCollection($collection->toArray());
        }

        return $collection;
    }

    public function getFilteredPersonalActions(): PersonalActionCollection
    {
        return $this->getPersonalActions()
            ->filter(function (PersonalActionInterface $personalAction) {
                return in_array(
                    $personalAction->getActionDefinition(),
                    $this->achievementDefinition->getActionDefinitions()->toArray()
                );
            })
            ->filter(function (PersonalActionInterface $personalAction) {
                return $personalAction->getAchievedAt() >= $this->achievementDefinition->getCreatedAt();
            });
    }
}
