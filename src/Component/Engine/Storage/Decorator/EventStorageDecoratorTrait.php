<?php

namespace Component\Engine\Storage\Decorator;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Component\Engine\Events;
use Component\Engine\Event\ObjectEvent;
use Component\Entity\PlayerInterface;
use Component\Entity\Achievement\PersonalAchievementInterface;
use Component\Entity\Achievement\PersonalActionInterface;

trait EventStorageDecoratorTrait
{
    use StorageDecoratorTrait {
        savePlayer              as invokeSavePlayer;
        savePersonalAchievement as invokeSavePersonalAchievement;
        savePersonalAction      as invokeSavePersonalAction;
        recalculatePlayerScore  as invokeRecalculatePlayerScore;
        recalculatePlayerLevel  as invokeRecalculatePlayerLevel;
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
        $event = new ObjectEvent($player);

        if ($this->isNew($player)) {
            $this->eventDispatcher->dispatch(Events::CREATE_PLAYER, $event);
        }

        $this->eventDispatcher->dispatch(Events::PRE_SAVE, $event);
        $this->invokeSavePlayer($player);
        $this->eventDispatcher->dispatch(Events::POST_SAVE, $event);
    }

    public function savePersonalAchievement(PersonalAchievementInterface $personalAchievement): void
    {
        $event = new ObjectEvent($personalAchievement);

        if ($this->isNew($personalAchievement)) {
            $this->eventDispatcher->dispatch(Events::GRANT_PERSONAL_ACHIEVEMENT, $event);
        }

        $this->eventDispatcher->dispatch(Events::PRE_SAVE, $event);
        $this->invokeSavePersonalAchievement($personalAchievement);
        $this->eventDispatcher->dispatch(Events::POST_SAVE, $event);
    }

    public function savePersonalAction(PersonalActionInterface $personalAction): void
    {
        $event = new ObjectEvent($personalAction);

        if ($this->isNew($personalAction)) {
            $this->eventDispatcher->dispatch(Events::GRANT_PERSONAL_ACTION, $event);
        }

        $this->eventDispatcher->dispatch(Events::PRE_SAVE, $event);
        $this->invokeSavePersonalAction($personalAction);
        $this->eventDispatcher->dispatch(Events::POST_SAVE, $event);
    }

    public function recalculatePlayerLevel(PlayerInterface $player): void
    {
        $event = new ObjectEvent($player);
        $level = $player->getLevel();

        $this->invokeRecalculatePlayerLevel($player);

        if ($player->getLevel() > $level) {
            $this->eventDispatcher->dispatch(Events::CHANGE_LEVEL, $event);
        }
    }

    public function recalculatePlayerScore(PlayerInterface $player): void
    {
        $event = new ObjectEvent($player);
        $score = $player->getScore();

        $this->invokeRecalculatePlayerScore($player);

        if ($player->getScore() > $score) {
            $this->eventDispatcher->dispatch(Events::CHANGE_SCORE, $event);
        }
    }
}
