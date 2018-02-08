<?php

namespace Component\Engine\EventListener;

use Component\Entity\Activity;
use Component\Entity\PlayerInterface;
use Component\Entity\Achievement\PersonalAchievementInterface;
use Component\Entity\Achievement\PersonalActionInterface;
use Component\Engine\Event\ObjectEvent;
use Component\Engine\Storage\StorageInterface;
use Component\Engine\Storage\Decorator\StorageDecoratorTrait;

class ActivityListener implements EventListenerInterface
{
    use StorageDecoratorTrait;

    public function __construct(StorageInterface $storage)
    {
        $this->setStorage($storage);
    }

    public function onCreatePlayer(ObjectEvent $event): void
    {
        /** @var PlayerInterface $player */
        $player = $event->getObject();

        $activity = new Activity(
            Activity::PLAYER_CREATED,
            $player,
            [
                'player' => $player->getUsername(),
                'created_at' => $player->getCreatedAt()->format('c'),
            ]
        );

        $this->getStorage()->saveActivity($activity);
    }

    public function onGrantPersonalAction(ObjectEvent $event): void
    {
        /** @var PersonalActionInterface $personalAction */
        $personalAction = $event->getObject();

        $activity = new Activity(
            Activity::PERSONAL_ACTION_GRANTED,
            $personalAction->getPlayer(),
            [
                'player' => $personalAction->getPlayer()->getUsername(),
                'action' => $personalAction->getActionDefinition()->getName(),
                'achieved_at' => $personalAction->getAchievedAt()->format('c'),
            ]
        );

        $this->getStorage()->saveActivity($activity);
    }

    public function onGrantPersonalAchievement(ObjectEvent $event): void
    {
        /** @var PersonalAchievementInterface $personalAchievement */
        $personalAchievement = $event->getObject();

        $activity = new Activity(
            Activity::PERSONAL_ACHIEVEMENT_GRANTED,
            $personalAchievement->getPlayer(),
            [
                'player' => $personalAchievement->getPlayer()->getUsername(),
                'achievement' => $personalAchievement->getAchievementDefinition()->getName(),
                'achieved_at' => $personalAchievement->getAchievedAt()->format('c'),
            ]
        );

        $this->getStorage()->saveActivity($activity);
    }

    public function onLevelChanged(ObjectEvent $event): void
    {
        /** @var PlayerInterface $player */
        $player = $event->getObject();

        $activity = new Activity(
            Activity::LEVEL_CHANGED,
            $player,
            [
                'player' => $player->getUsername(),
                'level' => $player->getLevel(),
            ]
        );

        $this->getStorage()->saveActivity($activity);
    }

    public function onScoreChanged(ObjectEvent $event): void
    {
        /** @var PlayerInterface $player */
        $player = $event->getObject();

        $activity = new Activity(
            Activity::SCORE_CHANGED,
            $player,
            [
                'player' => $player->getUsername(),
                'score' => $player->getScore(),
            ]
        );

        $this->getStorage()->saveActivity($activity);
    }
}
