<?php

namespace App\Integration\Tests\Command;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;
use App\Integration\Command\DisableCommand;
use App\Integration\Service\InstallerService;

class DisableCommandTest extends KernelTestCase
{
    public function test_execute(): void
    {
        self::bootKernel();
        $application = new Application(self::$kernel);
        $application->add(new DisableCommand());

        $installer = $this->getMockBuilder(InstallerService::class)
            ->disableOriginalConstructor()
            ->setMethods(['uninstall'])
            ->getMock();

        $installer->expects($this->once())
            ->method('uninstall');

        self::$kernel->getContainer()->set(InstallerService::class, $installer);

        $command = $application->find('yay:integration:disable');
        $commandTester = new CommandTester($command);

        $commandTester->execute(array(
            'command' => $command->getName(),
            'name' => 'test',
        ));

        $this->assertContains('[OK] Integration "test" disabled', $commandTester->getDisplay());
    }
}
