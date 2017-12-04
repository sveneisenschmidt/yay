<?php

namespace App\Mail\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use App\Mail\DependencyInjection\MailExtension;
use App\Mail\Mail;

class MailTest extends WebTestCase
{
    public function test_get_container_extension(): void
    {
        $this->assertInstanceOf(
            MailExtension::class,
            (new Mail())->getContainerExtension()
        );
    }
}
