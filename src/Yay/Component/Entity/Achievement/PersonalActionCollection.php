<?php

namespace Yay\Component\Entity\Achievement;

use Doctrine\Common\Collections\ArrayCollection;

class PersonalActionCollection extends ArrayCollection
{
    public function filterByAchievementDefinition(
        AchievementDefinitionInterface $achievementDefinition
    ): PersonalActionCollection
    {

    }

}
