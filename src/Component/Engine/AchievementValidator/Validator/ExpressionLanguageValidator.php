<?php

namespace Component\Engine\AchievementValidator\Validator;

use Symfony\Component\ExpressionLanguage\ExpressionLanguage;
use Component\Engine\AchievementValidatorInterface;
use Component\Engine\AchievementValidator\ValidationContext;
use Component\Entity\Achievement\AchievementDefinitionInterface;

class ExpressionLanguageValidator
    implements AchievementValidatorInterface, CalculableProgressInterface
{
    /** @var ExpressionLanguage */
    protected $language;

    /** @var string */
    protected $validationExpression;

    /** @var string */
    protected $calculationExpression;

    /** @var array */
    protected $supports;

    /** @var bool */
    protected $multiple = false;

    public function __construct(
        string $validationExpression,
        string $calculationExpression = '',
        array $supports = [],
        bool $multiple = false
    )
    {
        $this->language = new ExpressionLanguage();
        $this->validationExpression = $validationExpression;
        $this->calculationExpression = $calculationExpression;
        $this->supports = $supports;
        $this->multiple = $multiple;
    }

    public function validate(ValidationContext $validationContext): bool
    {
        return (bool) $this->language->evaluate(
            $this->validationExpression,
            [
                'player' => $validationContext->getPlayer(),
                'achievement' => $validationContext->getAchievementDefinition(),
                'actions' => $validationContext->getFilteredPersonalActions(),
            ]
        );
    }

    public function calculate(ValidationContext $validationContext): int
    {
        if (empty($this->calculationExpression)) {
            return 0;
        }

        return (int) $this->language->evaluate(
            $this->calculationExpression,
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
