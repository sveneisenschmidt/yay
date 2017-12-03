<?php

namespace App\Mail\EventListener;

use Symfony\Bundle\TwigBundle\TwigEngine;
use Swift_Mailer;
use Swift_Message;
use Component\Entity\Achievement\PersonalAchievement;
use Component\Entity\Achievement\PersonalAction;
use Component\Engine\Event\ObjectEvent;
use Component\Engine\EventListener\EventListenerInterface;

class MailListener implements EventListenerInterface
{
    /** @var TwigEngine */
    protected $renderer;

    /** @var Swift_Mailer */
    protected $mailer;

    public function __construct(TwigEngine $renderer, Swift_Mailer $mailer)
    {
        $this->renderer = $renderer;
        $this->mailer = $mailer;
    }

    public function onGrantPersonalAction(ObjectEvent $event): void
    {
        $personalAction = $event->getObject();
        $player = $personalAction->getPlayer();

        $contents = $this->renderer->render('Mail/grant_personal_action.html.twig', [
            'personalAction' => $personalAction,
        ]);

        $message = (new Swift_Message('Yay! A new action has been recorded for you!'))
            ->setFrom($player->getEmail(), 'No Reply')
            ->setTo($player->getEmail(), $player->getName())
            ->setBody($contents, 'text/html');

        $this->mailer->send($message);
    }

    public function onGrantPersonalAchievement(ObjectEvent $event): void
    {
        $personalAchievement = $event->getObject();
        $player = $personalAchievement->getPlayer();

        $contents = $this->renderer->render('Mail/grant_personal_achievement.html.twig', [
            'personalAchievement' => $personalAchievement,
        ]);

        $message = (new Swift_Message('Yay! You\'ve been awared a new achievement!'))
            ->setFrom($player->getEmail(), 'No Reply')
            ->setTo($player->getEmail(), $player->getName())
            ->setBody($contents, 'text/html');

        $this->mailer->send($message);
    }
}
