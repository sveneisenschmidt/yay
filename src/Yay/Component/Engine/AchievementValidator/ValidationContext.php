<?php

namespace Yay\Component\Engine\AchievementValidator;

use Yay\Component\Entity\Achievement\AchievementDefinitionInterface;
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
}
