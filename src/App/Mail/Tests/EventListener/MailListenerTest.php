<?php

namespace App\Mail\Tests\EventListener;

use Faker\Factory as FakerFactory;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Swift_Message;
use Component\Engine\Events;
use Component\Engine\Event\ObjectEvent;
use Component\Entity\Achievement\PersonalAchievementInterface;
use Component\Entity\Achievement\PersonalActionInterface;
use Component\Entity\Achievement\ActionDefinitionInterface;
use Component\Entity\PlayerInterface;
use App\Mail\EventListener\MailListener;
use App\Mail\Service\Mailer;

class MailListenerTest extends WebTestCase
{
    public function test_register(): void
    {
        $client = static::createClient();

        $container = $client->getKernel()->getContainer();
        $dispatcher = $container->get('event_dispatcher');

        $events = array_keys($dispatcher->getListeners());
        $this->assertContains(EVENTS::GRANT_PERSONAL_ACHIEVEMENT, $events);
        $this->assertContains(EVENTS::GRANT_PERSONAL_ACTION, $events);

        $calls = [];
        foreach ($dispatcher->getListeners() as $event => $listeners) {
            foreach ($listeners as $listener) {
                list($class, $method) = $listener;
                $calls []= sprintf('%s::%s', get_class($class), $method);
            }
        }

        $this->assertContains(sprintf('%s::%s', MailListener::class, 'onGrantPersonalAction'), $calls);
        $this->assertContains(sprintf('%s::%s', MailListener::class, 'onGrantPersonalAchievement'), $calls);
    }

    public function test_on_grant_personal_action(): void
    {
        $faker = FakerFactory::create();

        $mailer = $this->getMockBuilder(Mailer::class)
            ->disableOriginalConstructor()
            ->setMethods(['send', 'compose'])
            ->getMock();

        $mailer->expects($this->once())
            ->method('send')
            ->willReturn(0);

        $mailer->expects($this->once())
            ->method('compose')
            ->willReturn(new Swift_Message());

        $personalAction = $this->createConfiguredMock(PersonalActionInterface::class, [
            'getPlayer' => $this->createConfiguredMock(PlayerInterface::class, [
                'getEmail' => $faker->email
            ])
        ]);

        $event = new ObjectEvent($personalAction);
        $listener = new MailListener($mailer);
        $listener->onGrantPersonalAction($event);
    }
    
    public function test_on_grant_personal_achievement(): void
    {
        $faker = FakerFactory::create();
        
        $mailer = $this->getMockBuilder(Mailer::class)
            ->disableOriginalConstructor()
            ->setMethods(['send', 'compose'])
            ->getMock();

        $mailer->expects($this->once())
            ->method('send')
            ->willReturn(0);

        $mailer->expects($this->once())
            ->method('compose')
            ->willReturn(new Swift_Message());

        $personalAchievement = $this->createConfiguredMock(PersonalActionInterface::class, [
            'getPlayer' => $this->createConfiguredMock(PlayerInterface::class, [
                'getEmail' => $faker->email
            ])
        ]);

        $event = new ObjectEvent($personalAchievement);
        $listener = new MailListener($mailer);
        $listener->onGrantPersonalAchievement($event);
    }
}
