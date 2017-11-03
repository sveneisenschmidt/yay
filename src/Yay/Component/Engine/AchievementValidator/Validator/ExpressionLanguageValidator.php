<?php

namespace Yay\Component\Engine\AchievementValidator\Validator;

use Symfony\Component\ExpressionLanguage\ExpressionLanguage;
use Yay\Component\Engine\AchievementValidatorInterface;
use Yay\Component\Engine\AchievementValidator\ValidationContext;
use Yay\Component\Engine\AchievementValidator\ValidationHelper;
use Yay\Component\Entity\Achievement\AchievementDefinitionInterface;

class ExpressionLanguageValidator implements AchievementValidatorInterface
{
    /* @var ExpressionLanguage */
    protected $language;

    /* @var string */
    protected $expression;

    /* @var array */
    protected $supports;

    /* @var bool */
    protected $multiple = false;

    public function __construct(string $expression, array $supports = [], bool $multiple = false)
    {
        $this->language = new ExpressionLanguage();
        $this->expression = $expression;
        $this->supports = $supports;
        $this->multiple = $multiple;
    }

    public function validate(
        ValidationContext $validationContext,
        ValidationHelper $validationHelper
    ): bool {
        $player = $validationContext->getPlayer();
        $achievementDefinition = $validationContext->getAchievementDefinition();

        return $this->language->evaluate(
            $this->expression,
            [
                'player' => $player,
                'achievement' => $achievementDefinition,
                'personalActions' => $validationHelper->getPersonalActions($player),
                'filteredPersonalActions' => $validationHelper->getPersonalActionsByAchievement(
                    $player,
                    $achievementDefinition
                ),
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
