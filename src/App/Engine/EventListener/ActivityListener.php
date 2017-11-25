<?php

namespace App\Engine\EventListener;

use Component\Entity\Activity;
use Component\Engine\Event\ObjectEvent;
use Component\Engine\Storage\StorageInterface;
use Component\Engine\Storage\StorageTrait;
use Component\Entity\Achievement\PersonalAchievement;
use Component\Entity\Achievement\PersonalAction;

class ActivityListener
{
    use StorageTrait;

    public function __construct(StorageInterface $storage)
    {
        $this->setStorage($storage);
    }

    public function onGrantPersonalAction(ObjectEvent $event): void
    {
        /** @var PersonalAction $personalAction */
        $personalAction = $event->getObject();

        $activity = new Activity(Activity::PERSONAL_ACTION_GRANTED, [
            'player' => $personalAction->getPlayer()->getUsername(),
            'action' => $personalAction->getActionDefinition()->getName(),
            'achieved_at' => $personalAction->getAchievedAt()->format('c'),
        ]);

        $this->getStorage()->saveActivity($activity);
    }

    public function onGrantPersonalAchievement(ObjectEvent $event): void
    {
        /** @var PersonalAchievement $personalAchievement */
        $personalAchievement = $event->getObject();

        $activity = new Activity(Activity::PERSONAL_ACHIEVEMENT_GRANTED, [
            'player' => $personalAchievement->getPlayer()->getUsername(),
            'achievement' => $personalAchievement->getAchievementDefinition()->getName(),
            'achieved_at' => $personalAchievement->getAchievedAt()->format('c'),
        ]);

        $this->getStorage()->saveActivity($activity);
    }
}
