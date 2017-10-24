<?php

namespace Yay\Bundle\EngineBundle\Tests\Command;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;
use Yay\Bundle\EngineBundle\Command\RecalculateCommand;
use Yay\Component\Engine\Engine;
use Yay\Component\Entity\Player;
use Yay\Component\Entity\PlayerCollection;

class RecalculateCommandTest extends KernelTestCase
{
    /**
     * @test
     */
    public function execute()
    {
        self::bootKernel();
        $application = new Application(self::$kernel);
        $application->add(new RecalculateCommand());

        $engine = $this->getMockBuilder(Engine::class)
            ->disableOriginalConstructor()
            ->setMethods(['advance', 'findPlayerAny'])
            ->getMock();

        $engine->expects($this->atLeastonce())
            ->method('findPlayerAny')
            ->willReturn(new PlayerCollection([
                $this->createMock(Player::class),
                $this->createMock(Player::class),
            ]));

        $engine->expects($this->exactly(2))
            ->method('advance');

        self::$kernel->getContainer()->set(Engine::class, $engine);

        $command = $application->find('yay:recalculate');
        $commandTester = new CommandTester($command);

        $commandTester->execute(array(
            'command' => $command->getName(),
        ));

        $this->assertContains('[OK] Player progress recalculated', $commandTester->getDisplay());
    }
}
