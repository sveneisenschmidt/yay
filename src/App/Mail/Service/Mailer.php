<?php

namespace App\Mail\Service;

use Symfony\Bundle\TwigBundle\TwigEngine;
use Swift_Mailer;
use Swift_Message;

class Mailer
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

    public function send(Swift_Message $message): int
    {
        return $this->mailer->send($message);
    }

    public function compose(
        string $recipient,
        string $subject,
        string $template,
        array $data = []
    ): Swift_Message {
        $contents = $this->renderer->render($template, $data);

        return (new Swift_Message($subject))
            ->setFrom($recipient, 'No Reply')
            ->setTo($recipient)
            ->setBody($contents, 'text/html');
    }
}
