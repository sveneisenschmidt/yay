<?php

namespace Yay\Component\Engine;

use Doctrine\Common\Collections\Collection as CollectionInterface;

use Yay\Component\Entity\Achievement\GoalDefinitionInterface;
use Yay\Component\Entity\Achievement\StepCollection;
use Yay\Component\Entity\PlayerInterface;

interface GoalValidatorInterface
{
    /**
     * @param PlayerInterface           $player
     * @param GoalDefinitionInterface   $goalDefinition
     * @param StepCollection            $collection
     *
     * @return bool
     */
    public function validate(
        PlayerInterface $player,
        GoalDefinitionInterface $goalDefinition,
        StepCollection $collection
    ): bool;

    /**
     * @param GoalDefinitionInterface $goalDefinition
     *
     * @return bool
     */
    public function supports(GoalDefinitionInterface $goalDefinition): bool;
}