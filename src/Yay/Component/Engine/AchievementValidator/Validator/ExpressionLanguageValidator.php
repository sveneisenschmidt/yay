<?php

namespace Yay\Component\Engine\AchievementValidator\Validator;

use Symfony\Component\ExpressionLanguage\ExpressionLanguage;

use Yay\Component\Engine\AchievementValidatorInterface;
use Yay\Component\Entity\Achievement\AchievementDefinition;
use Yay\Component\Entity\Achievement\AchievementDefinitionInterface;
use Yay\Component\Entity\Achievement\PersonalActionCollection;
use Yay\Component\Entity\Achievement\PersonalActionInterface;
use Yay\Component\Entity\PlayerInterface;


class ExpressionLanguageValidator implements AchievementValidatorInterface
{
    /**
     * @var ExpressionLanguage
     */
    protected $language;

    /**
     * @var string
     */
    protected $expression;

    /**
     * @var array
     */
    protected $supports;

    /**
     * ExpressionLanguageValidator constructor.
     *
     * @param array  $handles
     * @param string $expression
     */
    public function __construct(string $expression, $supports = [])
    {
        $this->language = new ExpressionLanguage();
        $this->expression = $expression;
        $this->supports = $supports;
    }

    /**
     * {@inheritDoc}
     */
    public function validate(PlayerInterface $player, AchievementDefinitionInterface $achievementDefinition, PersonalActionCollection $collection): bool
    {
        $filteredCollection = $collection
            ->filter(function(PersonalActionInterface $personalAction) use ($achievementDefinition) {
                return in_array(
                    $personalAction->getActionDefinition(),
                    $achievementDefinition->getActionDefinitions()->toArray()
                );
            })
            ->filter(function(PersonalActionInterface $personalAction) use ($achievementDefinition) {
                return $personalAction->getAchievedAt() >= $achievementDefinition->getCreatedAt();
            });

        return $this->language->evaluate(
            $this->expression,
            [
                'player' => $player,
                'achievement' => $achievementDefinition,
                'personalActions' => $collection,
                'filteredPersonalActions' => $filteredCollection,
            ]
        );
    }

    /**
     * {@inheritDoc}
     */
    public function supports(AchievementDefinitionInterface $achievementDefinition): bool
    {
        return empty($this->supports) ? true : in_array($achievementDefinition->getName(), $this->supports);
    }
}
