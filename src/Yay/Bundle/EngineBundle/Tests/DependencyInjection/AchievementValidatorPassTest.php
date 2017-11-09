<?php

namespace Yay\Bundle\EngineBundle\Tests\DependencyInjection;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\ChildDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Yay\Bundle\EngineBundle\DependencyInjection\AchievementValidatorPass;

class AchievementValidatorPassTest extends WebTestCase
{
    /**
     * @test
     */
    public function process()
    {
        $definition1 = new ChildDefinition(\stdClass::class);
        $definition2 = new ChildDefinition(\stdClass::class);
        $definition3 = new ChildDefinition(\stdClass::class);

        $builder = $this->getMockBuilder(ContainerBuilder::class)
            ->setMethods(['getDefinition', 'findTaggedServiceIds'])
            ->getMock();

        $builder->expects($this->once())
            ->method('getDefinition')
            ->willReturn($definition1);

        $builder->expects($this->once())
            ->method('findTaggedServiceIds')
            ->willReturn([$definition2, $definition3]);

        (new AchievementValidatorPass())->process($builder);
        $this->assertCount(2, $definition1->getMethodCalls());
    }
}
