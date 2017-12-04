<?php

namespace App\Mail\Tests\EventListener;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Component\Engine\Events;

class MailListenerTest extends WebTestCase
{
    public function test_dispatch(): void
    {
        $client = static::createClient();

        $container = $client->getKernel()->getContainer();
        $dispatcher = $container->get('event_dispatcher');

        $events = array_keys($dispatcher->getListeners());
        $this->assertContains(EVENTS::GRANT_PERSONAL_ACHIEVEMENT, $events);
        $this->assertContains(EVENTS::GRANT_PERSONAL_ACTION, $events);

        foreach ($dispatcher->getListeners() as $event => $listeners) {
            if ($event == EVENTS::GRANT_PERSONAL_ACHIEVEMENT) {

            }
        }
    }
}
