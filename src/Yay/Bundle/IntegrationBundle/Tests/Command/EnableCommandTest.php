<?php

namespace Yay\Bundle\EngineBundle\Tests\Command;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;
use Yay\Bundle\IntegrationBundle\Command\EnableCommand;
use Yay\Bundle\IntegrationBundle\Service\InstallerService;

class EnableCommandTest extends KernelTestCase
{
    /**
     * @test
     */
    public function execute()
    {
        self::bootKernel();
        $application = new Application(self::$kernel);
        $application->add(new EnableCommand());

        $installer = $this->getMockBuilder(InstallerService::class)
            ->disableOriginalConstructor()
            ->setMethods(['install'])
            ->getMock();

        $installer->expects($this->once())
            ->method('install');

        self::$kernel->getContainer()->set(InstallerService::class, $installer);

        $command = $application->find('yay:integration:enable');
        $commandTester = new CommandTester($command);

        $commandTester->execute(array(
            'command' => $command->getName(),
            'name' => 'test',
            'path' => 'integration/test',
        ));

        $this->assertContains('[OK] Integration "test" enabled', $commandTester->getDisplay());
    }
}
