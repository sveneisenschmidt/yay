<?php

namespace Yay\Bundle\ApiBundle\Serializer\EventListener;

use JMS\Serializer\EventDispatcher\ObjectEvent;
use JMS\Serializer\GenericSerializationVisitor;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Yay\Component\Entity\Achievement\AchievementDefinitionInterface;
use Yay\Component\Entity\Achievement\ActionDefinitionInterface;
use Yay\Component\Entity\Achievement\PersonalAchievementInterface;
use Yay\Component\Entity\Achievement\PersonalActionInterface;
use Yay\Component\Entity\PlayerInterface;

class LinkListener
{
    /**
     * @var UrlGeneratorInterface
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

        if ($event->getObject() instanceof AchievementDefinitionInterface) {
            $this->handleAchievementDefinition($visitor, $event->getObject());
        }

        if ($event->getObject() instanceof PersonalAchievementInterface) {
            $this->handlePersonalAchievement($visitor, $event->getObject());
        }

        if ($event->getObject() instanceof PersonalActionInterface) {
            $this->handlePersonalAction($visitor, $event->getObject());
        }
    }

    /**
     * @param GenericSerializationVisitor $visitor
     * @param PlayerInterface             $player
     */
    public function handlePlayer(GenericSerializationVisitor $visitor, PlayerInterface $player)
    {
        $visitor->setData('links', [
            'self' => $this->generateRoute(
                'api_player_show',
                ['username' => $player->getUsername()]
            ),
            'personal_achievements' => $this->generateRoute(
                'api_player_personal_achievements_show',
                ['username' => $player->getUsername()]
            ),
            'personal_actions' => $this->generateRoute(
                'api_player_personal_actions_show',
                ['username' => $player->getUsername()]
            ),
        ]);
    }

    /**
     * @param GenericSerializationVisitor    $visitor
     * @param AchievementDefinitionInterface $achievementDefinition
     */
    public function handleAchievementDefinition(GenericSerializationVisitor $visitor, AchievementDefinitionInterface $achievementDefinition)
    {
        $visitor->setData('links', [
            'self' => $this->generateRoute(
                'api_achievement_show',
                ['name' => $achievementDefinition->getName()]
            ),
            'actions' => array_map(function (ActionDefinitionInterface $actionDefinition) {
                return $this->generateRoute(
                    'api_action_show',
                    ['name' => $actionDefinition->getName()]
                );
            }, $achievementDefinition->getActionDefinitions()->toArray()),
        ]);
    }

    /**
     * @param GenericSerializationVisitor $visitor
     * @param ActionDefinitionInterface   $achievementDefinition
     */
    public function handleActionDefinition(GenericSerializationVisitor $visitor, ActionDefinitionInterface $actionDefinition)
    {
        $visitor->setData('links', [
            'self' => $this->generateRoute(
                'api_action_show',
                ['name' => $actionDefinition->getName()]
            ),
        ]);
    }

    /**
     * @param GenericSerializationVisitor $visitor
     * @param ActionDefinitionInterface   $achievementDefinition
     */
    public function handlePersonalAchievement(GenericSerializationVisitor $visitor, PersonalAchievementInterface $personalAchievement)
    {
        $visitor->setData('links', [
            'self' => $this->generateRoute(
                'api_player_personal_achievements_show',
                ['username' => $personalAchievement->getPlayer()->getUsername()]
            ),
            'player' => $this->generateRoute(
                'api_player_show',
                ['username' => $personalAchievement->getPlayer()->getUsername()]
            ),
            'achievement' => $this->generateRoute(
                'api_achievement_show',
                ['name' => $personalAchievement->getAchievementDefinition()->getName()]
            ),
        ]);
    }

    /**
     * @param GenericSerializationVisitor $visitor
     * @param PersonalActionInterface     $achievementDefinition
     */
    public function handlePersonalAction(GenericSerializationVisitor $visitor, PersonalActionInterface $personalAction)
    {
        $visitor->setData('links', [
            'self' => $this->generateRoute(
                'api_player_personal_actions_show',
                ['username' => $personalAction->getPlayer()->getUsername()]
            ),
            'player' => $this->generateRoute(
                'api_player_show',
                ['username' => $personalAction->getPlayer()->getUsername()]
            ),
            'action' => $this->generateRoute(
                'api_action_show',
                ['name' => $personalAction->getActionDefinition()->getName()]
            ),
        ]);
    }
}
