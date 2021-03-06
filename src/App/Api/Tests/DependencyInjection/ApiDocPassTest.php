<?php

namespace App\Api\Tests\DependencyInjection;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\ChildDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use App\Api\DependencyInjection\ApiDocPass;
use App\Api\Formatter\MarkdownFormatter;

class ApiDocPassTest extends WebTestCase
{
    public function test_process(): void
    {
        $definition = new ChildDefinition(\stdClass::class);

        $builder = $this->getMockBuilder(ContainerBuilder::class)
            ->setMethods(['getDefinition'])
            ->getMock();

        $builder->expects($this->once())
            ->method('getDefinition')
            ->willReturn($definition);

        (new ApiDocPass())->process($builder);
        $this->assertEquals($definition->getClass(), MarkdownFormatter::class);
    }
}
