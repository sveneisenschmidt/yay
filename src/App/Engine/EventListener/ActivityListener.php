<?php

namespace App\Engine\EventListener;

use Component\Entity\Activity;
use Component\Engine\Event\ObjectEvent;
use Component\Engine\Storage\StorageInterface;
use Component\Engine\Storage\StorageTrait;
use Component\Entity\Achievement\PersonalAchievement;

class ActivityListener
{
    use StorageTrait;

    public function __construct(StorageInterface $storage)
    {
        $this->setStorage($storage);
    }

    public function onPreSave(ObjectEvent $event): void
    {
    }

    public function onPostSave(ObjectEvent $event): void
    {
    }

    public function onGrantAchievement(ObjectEvent $event): void
    {
        /** @var PersonalAchievement $personalAchievement */
        $personalAchievement = $event->getObject();

        $activity = new Activity(Activity::ACHIEVEMENT_GRANTED, [
            'player' => $personalAchievement->getPlayer()->getUsername(),
            'achievement' => $personalAchievement->getAchievementDefinition()->getName(),
            'achieved_at' => $personalAchievement->getAchievedAt()
        ]);

        $this->getStorage()->saveActivity($activity);
    }
}
