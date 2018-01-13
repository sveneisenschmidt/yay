<?php

namespace App\Mail\EventListener;

use App\Mail\Service\Mailer;
use Component\Entity\PlayerInterface;
use Component\Entity\Achievement\PersonalAchievementInterface;
use Component\Entity\Achievement\PersonalActionInterface;
use Component\Engine\Event\ObjectEvent;
use Component\Engine\EventListener\EventListenerInterface;

class MailListener implements EventListenerInterface
{
    /** @var Mailer */
    protected $mailer;

    public function __construct(Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    public function onCreatePlayer(ObjectEvent $event): void
    {
        /** @var PlayerInterface $player */
        $player = $event->getObject();

        $this->mailer->send($this->mailer->compose(
            $player->getEmail(),
            'Yay! Welcome on board!!',
            'Mail/create_player.html.twig',
            ['player' => $player]
        ));
    }

    public function onGrantPersonalAction(ObjectEvent $event): void
    {
        /** @var PersonalActionInterface $personalAction */
        $personalAction = $event->getObject();
        $player = $personalAction->getPlayer();

        $this->mailer->send($this->mailer->compose(
            $player->getEmail(),
            'Yay! A new action has been recorded for you!',
            'Mail/grant_personal_action.html.twig',
            ['personalAction' => $personalAction]
        ));
    }

    public function onGrantPersonalAchievement(ObjectEvent $event): void
    {
        /** @var PersonalAchievementInterface $personalAchievement */
        $personalAchievement = $event->getObject();
        $player = $personalAchievement->getPlayer();

        $this->mailer->send($this->mailer->compose(
            $player->getEmail(),
            'Yay! You\'ve been awared a new achievement!',
            'Mail/grant_personal_achievement.html.twig',
            ['personalAchievement' => $personalAchievement]
        ));
    }
}
