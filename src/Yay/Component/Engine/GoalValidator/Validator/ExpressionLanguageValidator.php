<?php

namespace Yay\Component\Engine\GoalValidator\Validator;

use Symfony\Component\ExpressionLanguage\ExpressionLanguage;

use Yay\Component\Engine\GoalValidatorInterface;
use Yay\Component\Entity\Achievement\GoalDefinition;
use Yay\Component\Entity\Achievement\GoalDefinitionInterface;
use Yay\Component\Entity\Achievement\StepCollection;
use Yay\Component\Entity\Achievement\StepInterface;
use Yay\Component\Entity\PlayerInterface;


class ExpressionLanguageValidator implements GoalValidatorInterface
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
    public function validate(PlayerInterface $player, GoalDefinitionInterface $goalDefinition, StepCollection $collection): bool
    {
        $filteredCollection = $collection
            ->filter(function(StepInterface $step) use ($goalDefinition) {
                return in_array(
                    $step->getActionDefinition(),
                    $goalDefinition->getActionDefinitions()->toArray()
                );
            })
            ->filter(function(StepInterface $step) use ($goalDefinition) {
                return $step->getAchievedAt() >= $goalDefinition->getCreatedAt();
            });

        return $this->language->evaluate(
            $this->expression,
            [
                'player' => $player,
                'goal' => $goalDefinition,
                'steps' => $collection,
                'filteredSteps' => $filteredCollection,
            ]
        );
    }

    /**
     * {@inheritDoc}
     */
    public function supports(GoalDefinitionInterface $goalDefinition): bool
    {
        return empty($this->supports) ? true : in_array($goalDefinition->getName(), $this->supports);
    }
}