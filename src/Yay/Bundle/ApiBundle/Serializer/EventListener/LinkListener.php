<?php

namespace Yay\Bundle\ApiBundle\Serializer\EventListener;

use Doctrine\Common\Collections\ArrayCollection;
use JMS\Serializer\EventDispatcher\ObjectEvent;
use JMS\Serializer\GenericSerializationVisitor;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

use Yay\Component\Entity\Achievement\ActionDefinitionInterface;
use Yay\Component\Entity\Achievement\PersonalAchievementInterface;
use Yay\Component\Entity\Achievement\StepInterface;
use Yay\Component\Entity\PlayerInterface;
use Yay\Component\Entity\Achievement\GoalDefinitionInterface;


class LinkListener
{
    /**
     * @type UrlGeneratorInterface
     */
    protected $urlGenerator;

    /**
     * LinkListener constructor.
     *
     * @param UrlGeneratorInterface $urlGenerator
     */
    public function __construct(UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * @param       $routeName
     * @param array $routeParams
     *
     * @return string
     */
    public function generateRoute($routeName, array $routeParams = []): string
    {
        return $this->urlGenerator->generate($routeName, $routeParams, UrlGeneratorInterface::ABSOLUTE_URL);
    }

    /**
     * @param ObjectEvent $event
     */
    public function onPostSerialize(ObjectEvent $event)
    {
        /** @var GenericSerializationVisitor $visitor */
        $visitor = $event->getVisitor();

        if ($event->getObject() instanceof PlayerInterface) {
            $this->handlePlayer($visitor, $event->getObject());
        }

        if ($event->getObject() instanceof ActionDefinitionInterface) {
            $this->handleActionDefinition($visitor, $event->getObject());
        }

        if ($event->getObject() instanceof GoalDefinitionInterface) {
            $this->handleGoalDefinition($visitor, $event->getObject());
        }

        if ($event->getObject() instanceof PersonalAchievementInterface) {
            $this->handlePersonalAchievement($visitor, $event->getObject());
        }

        if ($event->getObject() instanceof StepInterface) {
            $this->handleStep($visitor, $event->getObject());
        }
    }

    /**
     * @param GenericSerializationVisitor $visitor
     * @param PlayerInterface  $player
     */
    public function handlePlayer(GenericSerializationVisitor $visitor, PlayerInterface $player)
    {
        $visitor->setData('links', [
            'self' => $this->generateRoute(
                'player_show',
                ['username' => $player->getUsername()]
            ),
            'personal_achievements' => $this->generateRoute(
                'player_personal_achievements_show',
                ['username' => $player->getUsername()]
            ),
            'personal_actions' => $this->generateRoute(
                'player_personal_actions_show',
                ['username' => $player->getUsername()]
            ),
        ]);
    }

    /**
     * @param GenericSerializationVisitor $visitor
     * @param GoalDefinitionInterface  $goalDefinition
     */
    public function handleGoalDefinition(GenericSerializationVisitor $visitor, GoalDefinitionInterface $goalDefinition)
    {
        $visitor->setData('links', [
            'self' => $this->generateRoute(
                'achievement_show',
                ['name' => $goalDefinition->getName()]
            ),
            'actions' => array_map(function(ActionDefinitionInterface $actionDefinition) {
                return $this->generateRoute(
                    'action_show',
                    ['name' => $actionDefinition->getName()]
                );
            }, $goalDefinition->getActionDefinitions()->toArray()),
        ]);
    }

    /**
     * @param GenericSerializationVisitor $visitor
     * @param ActionDefinitionInterface  $goalDefinition
     */
    public function handleActionDefinition(GenericSerializationVisitor $visitor, ActionDefinitionInterface $actionDefinition)
    {

        $visitor->setData('links', [
            'self' => $this->generateRoute(
                'action_show',
                ['name' => $actionDefinition->getName()]
            ),
        ]);
    }

    /**
     * @param GenericSerializationVisitor $visitor
     * @param ActionDefinitionInterface  $goalDefinition
     */
    public function handlePersonalAchievement(GenericSerializationVisitor $visitor, PersonalAchievementInterface $personalAchievement)
    {
        $visitor->setData('links', [
            'self' => $this->generateRoute(
                'player_personal_achievements_show',
                ['username' => $personalAchievement->getPlayer()->getUsername()]
            ),
            'player' => $this->generateRoute(
                'player_show',
                ['username' => $personalAchievement->getPlayer()->getUsername()]
            ),
            'achievement' => $this->generateRoute(
                'achievement_show',
                ['name' => $personalAchievement->getGoalDefinition()->getName()]
            ),
        ]);
    }

    /**
     * @param GenericSerializationVisitor $visitor
     * @param StepInterface  $goalDefinition
     */
    public function handleStep(GenericSerializationVisitor $visitor, StepInterface $step)
    {

        $visitor->setData('links', [
            'self' => $this->generateRoute(
                'player_personal_actions_show',
                ['username' => $step->getPlayer()->getUsername()]
            ),
            'player' => $this->generateRoute(
                'player_show',
                ['username' => $step->getPlayer()->getUsername()]
            ),
            'action' => $this->generateRoute(
                'action_show',
                ['name' => $step->getActionDefinition()->getName()]
            ),
        ]);
    }

}
