<?php

namespace Yay\Component\Engine;

use Doctrine\Common\Collections\Collection as CollectionInterface;

use Yay\Component\Entity\Achievement\AchievementDefinitionInterface;
use Yay\Component\Entity\Achievement\PersonalActionCollection;
use Yay\Component\Entity\PlayerInterface;

interface AchievementValidatorInterface
{
    /**
     * @param PlayerInterface           $player
     * @param AchievementDefinitionInterface   $achievementDefinition
     * @param PersonalActionCollection            $collection
     *
     * @return bool
     */
    public function validate(
        PlayerInterface $player,
        AchievementDefinitionInterface $achievementDefinition,
        PersonalActionCollection $collection
    ): bool;

    /**
     * @param AchievementDefinitionInterface $achievementDefinition
     *
     * @return bool
     */
    public function supports(AchievementDefinitionInterface $achievementDefinition): bool;
}
