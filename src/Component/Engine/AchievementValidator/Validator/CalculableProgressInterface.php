<?php

namespace Component\Engine\AchievementValidator\Validator;

use Component\Engine\AchievementValidator\ValidationContext;

interface CalculableProgressInterface
{
    public function calculate(ValidationContext $validationContext): int;
}
