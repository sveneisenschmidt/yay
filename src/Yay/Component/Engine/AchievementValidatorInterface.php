<?php

namespace Yay\Component\Engine;

use Yay\Component\Engine\AchievementValidator\ValidationContext;
use Yay\Component\Entity\Achievement\AchievementDefinitionInterface;

interface AchievementValidatorInterface
{
    public function validate(ValidationContext $validationContext): bool;

    public function supports(AchievementDefinitionInterface $achievementDefinition): bool;

    public function multiple(): bool;
}
