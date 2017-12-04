<?php

namespace App\Mail\Tests\DependencyInjection;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use App\Mail\DependencyInjection\MailExtension;

class MailExtensionTest extends WebTestCase
{
    public function test_load(): void
    {
        $builder = $this->getMockBuilder(ContainerBuilder::class)
            ->setMethods(['getParameter'])
            ->getMock();

        $builder->expects($this->once())
            ->method('getParameter')
            ->willReturn('test');

        (new MailExtension())->load([], $builder);
    }
}
