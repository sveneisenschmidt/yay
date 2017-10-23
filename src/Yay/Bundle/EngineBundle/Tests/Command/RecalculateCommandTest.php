<?php

namespace Yay\Bundle\EngineBundle\Tests\Command;

use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Console\Input\Input;
use Symfony\Component\Console\Output\Output;
use Yay\Bundle\EngineBundle\Command\RecalculateCommand;
use Yay\Component\Engine\Engine;
use Yay\Component\Entity\Player;
use Yay\Component\Entity\PlayerCollection;

class RecalculateCommandTest extends TestCase
{
    /**
     * @test
     */
    public function configure()
    {
        $command = new RecalculateCommand();
        $this->assertEquals('yay:recalculate', $command->getName());
    }

    /**
     * @test
     */
    public function execute()
    {
        $engine = $this->getMockBuilder(Engine::class)
            ->disableOriginalConstructor()
            ->setMethods(['findPlayerAny', 'advance'])
            ->getMock();

        $engine->expects($this->atLeastonce())
            ->method('findPlayerAny')
            ->willReturn(new PlayerCollection([
                $this->createMock(Player::class),
                $this->createMock(Player::class),
            ]));

        $engine->expects($this->exactly(2))
            ->method('advance');

        $container = $this->getMockBuilder(ContainerInterface::class)
            ->disableOriginalConstructor()
            ->setMethods(get_class_methods(ContainerInterface::class))
            ->getMock();

        $container->expects($this->atLeastonce())
            ->method('get')
            ->willReturn($engine);

        $command = new RecalculateCommand();
        $command->setContainer($container);
        $command->run(
            $this->createMock(Input::class),
            $this->createMock(Output::class)
        );
    }
}
