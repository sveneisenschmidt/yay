<?php

namespace Yay\Component\Engine;

use Yay\Component\Engine\AchievementValidator\ValidationContext;
use Yay\Component\Engine\AchievementValidator\ValidationHelper;
use Yay\Component\Entity\Achievement\AchievementDefinitionInterface;

interface AchievementValidatorInterface
{
    public function validate(ValidationContext $validationContext, ValidationHelper $validationHelper): bool;

    public function supports(AchievementDefinitionInterface $achievementDefinition): bool;

    public function multiple(): bool;
}
