<?php

namespace Component\Engine\Storage;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Component\Engine\Events;
use Component\Engine\Event\ObjectEvent;
use Component\Entity\PlayerInterface;
use Component\Entity\Achievement\PersonalAchievementInterface;
use Component\Entity\Achievement\PersonalActionInterface;

trait EventStorageTrait
{
    use StorageTrait {
        savePlayer              as invokeSavePlayer;
        savePersonalAchievement as invokeSavePersonalAchievement;
        savePersonalAction      as invokesavePersonalAction;
    }

    /** @var EventDispatcherInterface */
    protected $eventDispatcher;

    public function setEventDispatcher(EventDispatcherInterface $eventDispatcher): void
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    public function getEventDispatcher(): EventDispatcherInterface
    {
        return $this->eventDispatcher;
    }

    public function savePlayer(PlayerInterface $player): void
    {
        $this->eventDispatcher->dispatch(Events::PRE_SAVE, $event = new ObjectEvent($player));
        $this->invokeSavePlayer($player);
        $this->eventDispatcher->dispatch(Events::POST_SAVE, $event);
    }

    public function savePersonalAchievement(PersonalAchievementInterface $personalAchievement): void
    {
        $this->eventDispatcher->dispatch(Events::PRE_SAVE, $event = new ObjectEvent($personalAchievement));
        $this->invokeSavePersonalAchievement($personalAchievement);
        $this->eventDispatcher->dispatch(Events::POST_SAVE, $event);
    }

    public function savePersonalAction(PersonalActionInterface $personalAction): void
    {
        $this->eventDispatcher->dispatch(Events::PRE_SAVE, $event = new ObjectEvent($personalAction));
        $this->invokesavePersonalAction($personalAction);
        $this->eventDispatcher->dispatch(Events::POST_SAVE, $event);
    }
}
