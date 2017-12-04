<?php

namespace App\Integration\Tests\Service;

use Faker\Factory as FakerFactory;
use PHPUnit\Framework\TestCase;
use Swift_Mailer;
use Swift_Message;
use Symfony\Bundle\TwigBundle\TwigEngine;
use App\Mail\Service\Mailer;

class MailerTest extends TestCase
{
    public function test_compose()
    {
        $faker = FakerFactory::create();
        $mailer = new Mailer(
            $renderer = $this->createMock(TwigEngine::class),
            $mail = $this->createMock(Swift_Mailer::class)
        );

        $message = $mailer->compose(
            $recipient = $faker->email,
            $subject = $faker->text,
            $template = $faker->text,
            []
        );

        $this->assertInstanceOf(Swift_Message::class, $message);
    }

    public function test_send()
    {
        $faker = FakerFactory::create();
        $mailer = new Mailer(
            $renderer = $this->createMock(TwigEngine::class),
            $mail = $this->createConfiguredMock(Swift_Mailer::class, [
                'send' => $status = rand(0, 10),
            ])
        );

        $message = $mailer->compose(
            $recipient = $faker->email,
            $subject = $faker->text,
            $template = $faker->text,
            []
        );

        $this->assertEquals($status, $mailer->send($message));
    }
}
