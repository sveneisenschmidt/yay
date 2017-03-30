<?php

namespace Yay\Component\Entity\Achievement;

use Doctrine\Common\Collections\ArrayCollection;

class StepCollection extends ArrayCollection
{
    public function filterByGoalDefinition(
        GoalDefinitionInterface $goalDefinition
    ): StepCollection
    {

    }

}
