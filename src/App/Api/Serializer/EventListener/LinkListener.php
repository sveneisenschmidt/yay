<?php

namespace App\Api\Serializer\EventListener;

use JMS\Serializer\EventDispatcher\ObjectEvent;
use JMS\Serializer\GenericSerializationVisitor;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Component\Entity\Achievement\AchievementDefinitionInterface;
use Component\Entity\Achievement\ActionDefinitionInterface;
use Component\Entity\Achievement\PersonalAchievementInterface;
use Component\Entity\Achievement\PersonalActionInterface;
use Component\Entity\PlayerInterface;
use Component\Entity\ActivityInterface;
use Component\Entity\Activity;

class LinkListener
{
    /** @var UrlGeneratorInterface */
    protected $urlGenerator;

    public function __construct(UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }

    public function generateRoute(
        string $routeName,
        array $routeParams = []
    ): string {
        return $this->urlGenerator->generate(
            $routeName,
            $routeParams,
            UrlGeneratorInterface::ABSOLUTE_URL
        );
    }

    public function onPostSerialize(ObjectEvent $event): void
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

        if ($event->getObject() instanceof ActivityInterface) {
            $this->handleActivity($visitor, $event->getObject());
        }
    }

    public function handlePlayer(
        GenericSerializationVisitor $visitor,
        PlayerInterface $player
    ): void {
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
            'personal_activities' => $this->generateRoute(
                'api_player_personal_activities_show',
                ['username' => $player->getUsername()]
            ),
        ]);
    }

    public function handleAchievementDefinition(
        GenericSerializationVisitor $visitor,
        AchievementDefinitionInterface $achievementDefinition
    ): void {
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

    public function handleActionDefinition(
        GenericSerializationVisitor $visitor,
        ActionDefinitionInterface $actionDefinition
    ): void {
        $visitor->setData('links', [
            'self' => $this->generateRoute(
                'api_action_show',
                ['name' => $actionDefinition->getName()]
            ),
        ]);
    }

    public function handlePersonalAchievement(
        GenericSerializationVisitor $visitor,
        PersonalAchievementInterface $personalAchievement
    ): void {
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

    public function handlePersonalAction(
        GenericSerializationVisitor $visitor,
        PersonalActionInterface $personalAction
    ): void {
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

    public function handleActivity(
        GenericSerializationVisitor $visitor,
        ActivityInterface $activity
    ): void {
        if (Activity::PLAYER_CREATED == $activity->getName()) {
            $this->handleActivityPlayerCreated($visitor, $activity);
        }

        if (Activity::PERSONAL_ACHIEVEMENT_GRANTED == $activity->getName()) {
            $this->handleActivityPersonalAchievementGranted($visitor, $activity);
        }

        if (Activity::PERSONAL_ACTION_GRANTED == $activity->getName()) {
            $this->handleActivityPersonalActionGranted($visitor, $activity);
        }

        if (Activity::LEVEL_CHANGED == $activity->getName()) {
            $this->handleActivityLevelChanged($visitor, $activity);
        }

        if (Activity::SCORE_CHANGED == $activity->getName()) {
            $this->handleActivityScoreChanged($visitor, $activity);
        }
    }

    public function handleActivityPlayerCreated(
        GenericSerializationVisitor $visitor,
        ActivityInterface $activity
    ): void {
        $visitor->setData('links', [
            'self' => $this->generateRoute(
                'api_activity_index',
                []
            ),
            'player' => $this->generateRoute(
                'api_player_show',
                ['username' => $activity->getPlayer()->getUsername()]
            ),
        ]);
    }

    public function handleActivityLevelChanged(
        GenericSerializationVisitor $visitor,
        ActivityInterface $activity
    ): void {
        $visitor->setData('links', [
            'self' => $this->generateRoute(
                'api_activity_index',
                []
            ),
            'player' => $this->generateRoute(
                'api_player_show',
                ['username' => $activity->getPlayer()->getUsername()]
            ),
        ]);
    }

    public function handleActivityScoreChanged(
        GenericSerializationVisitor $visitor,
        ActivityInterface $activity
    ): void {
        $visitor->setData('links', [
            'self' => $this->generateRoute(
                'api_activity_index',
                []
            ),
            'player' => $this->generateRoute(
                'api_player_show',
                ['username' => $activity->getPlayer()->getUsername()]
            ),
        ]);
    }

    public function handleActivityPersonalActionGranted(
        GenericSerializationVisitor $visitor,
        ActivityInterface $activity
    ): void {
        $visitor->setData('links', [
            'self' => $this->generateRoute(
                'api_activity_index',
                []
            ),
            'player' => $this->generateRoute(
                'api_player_show',
                ['username' => $activity->getPlayer()->getUsername()]
            ),
            'action' => $this->generateRoute(
                'api_action_show',
                ['name' => $activity->getData()['action']]
            ),
        ]);
    }

    public function handleActivityPersonalAchievementGranted(
        GenericSerializationVisitor $visitor,
        ActivityInterface $activity
    ): void {
        $visitor->setData('links', [
            'self' => $this->generateRoute(
                'api_activity_index',
                []
            ),
            'player' => $this->generateRoute(
                'api_player_show',
                ['username' => $activity->getPlayer()->getUsername()]
            ),
            'achievement' => $this->generateRoute(
                'api_achievement_show',
                ['name' => $activity->getData()['achievement']]
            ),
        ]);
    }
}
