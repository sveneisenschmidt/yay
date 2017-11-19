<?php

namespace Component\Engine;

use Component\Engine\AchievementValidator\ValidationContext;
use Component\Entity\Achievement\AchievementDefinitionInterface;

interface AchievementValidatorInterface
{
    public function validate(ValidationContext $validationContext): bool;

    public function supports(AchievementDefinitionInterface $achievementDefinition): bool;

    public function multiple(): bool;
}
