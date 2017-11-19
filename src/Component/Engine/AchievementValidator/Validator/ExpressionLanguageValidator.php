<?php

namespace Component\Engine\AchievementValidator\Validator;

use Symfony\Component\ExpressionLanguage\ExpressionLanguage;
use Component\Engine\AchievementValidatorInterface;
use Component\Engine\AchievementValidator\ValidationContext;
use Component\Entity\Achievement\AchievementDefinitionInterface;

class ExpressionLanguageValidator implements AchievementValidatorInterface
{
    /** @var ExpressionLanguage */
    protected $language;

    /** @var string */
    protected $expression;

    /** @var array */
    protected $supports;

    /** @var bool */
    protected $multiple = false;

    public function __construct(string $expression, array $supports = [], bool $multiple = false)
    {
        $this->language = new ExpressionLanguage();
        $this->expression = $expression;
        $this->supports = $supports;
        $this->multiple = $multiple;
    }

    public function validate(ValidationContext $validationContext): bool
    {
        return $this->language->evaluate(
            $this->expression,
            [
                'player' => $validationContext->getPlayer(),
                'achievement' => $validationContext->getAchievementDefinition(),
                'actions' => $validationContext->getFilteredPersonalActions(),
            ]
        );
    }

    public function supports(AchievementDefinitionInterface $achievementDefinition): bool
    {
        return empty($this->supports) ? true : in_array($achievementDefinition->getName(), $this->supports);
    }

    public function multiple(): bool
    {
        return $this->multiple;
    }
}
