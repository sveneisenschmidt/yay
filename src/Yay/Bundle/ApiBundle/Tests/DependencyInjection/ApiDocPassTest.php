<?php

namespace Yay\Bundle\ApiBundle\Tests\DependencyInjection;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\ChildDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Yay\Bundle\ApiBundle\DependencyInjection\ApiDocPass;
use Yay\Bundle\ApiBundle\Formatter\MarkdownFormatter;

class ApiDocPassTest extends WebTestCase
{
    /**
     * @test
     */
    public function process()
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
