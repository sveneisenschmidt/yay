<?php

namespace App\Integration\Tests\DependencyInjection;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\ChildDefinition;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use App\Integration\DependencyInjection\IncomingChainProcessorPass;
use Component\Webhook\Incoming\Processor\ChainProcessor;
use Component\Webhook\Incoming\Processor\DummyProcessor;

class IncomingChainProcessorPassTest extends WebTestCase
{
    public function test_process(): void
    {
        $definition = new ChildDefinition(ChainProcessor::class);
        $definition->setClass(ChainProcessor::class);

        $builder = $this->getMockBuilder(ContainerBuilder::class)
            ->setMethods(['findTaggedServiceIds', 'findDefinition', 'processChainProcessor'])
            ->getMock();

        $builder->expects($this->once())
            ->method('findTaggedServiceIds')
            ->willReturn([$definition]);

        $builder->expects($this->once())
            ->method('findDefinition')
            ->willReturn($definition);

        (new IncomingChainProcessorPass())->process($builder);
    }

    public function test_process_chainprocessor() 
    {
        $definition = new ChildDefinition(ChainProcessor::class);
        $definition->setClass(ChainProcessor::class);
        $definition->setArguments([
            'test', ['reference1', 'reference2', 'reference3']
        ]);

        $builder = $this->getMockBuilder(ContainerBuilder::class)
            ->getMock();

        (new IncomingChainProcessorPass())->processChainProcessor($builder, $definition);

        $arguments = $definition->getArguments();
        $this->assertInternalType('array', $arguments);
        $this->assertNotEmpty($arguments);
        $this->assertArrayHasKey(0, $arguments);
        $this->assertArrayHasKey(1, $arguments);
        $this->assertInternalType('array', $arguments[1]);
        $this->assertNotEmpty($arguments[1]);
        foreach ($arguments[1] as $reference) {
            $this->assertInstanceof(Reference::class, $reference);
        }
    }

    public function test_process_chainprocessor_no_arguments() 
    {
        $definition = new ChildDefinition(ChainProcessor::class);
        $definition->setClass(ChainProcessor::class);
        $definition->setArguments([]);

        $builder = $this->getMockBuilder(ContainerBuilder::class)
            ->getMock();

        (new IncomingChainProcessorPass())->processChainProcessor($builder, $definition);

        $arguments = $definition->getArguments();
        $this->assertInternalType('array', $arguments);
        $this->assertEmpty($arguments);
    }

    public function test_process_chainprocessor_no_array() 
    {
        $definition = new ChildDefinition(ChainProcessor::class);
        $definition->setClass(ChainProcessor::class);
        $definition->setArguments(['test', 'test']);

        $builder = $this->getMockBuilder(ContainerBuilder::class)
            ->getMock();

        (new IncomingChainProcessorPass())->processChainProcessor($builder, $definition);

        $arguments = $definition->getArguments();
        $this->assertInternalType('array', $arguments);
        $this->assertNotEmpty($arguments);
        $this->assertArrayHasKey(0, $arguments);
        $this->assertArrayHasKey(1, $arguments);
        $this->assertInternalType('string', $arguments[0]);
        $this->assertInternalType('string', $arguments[1]);
    }
}