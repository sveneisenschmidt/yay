<?php

namespace Yay\Bundle\EngineBundle\Tests\Command;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;
use Yay\Bundle\IntegrationBundle\Command\ValidateCommand;
use Yay\Bundle\IntegrationBundle\Service\InstallerService;

class ValidateCommandTest extends KernelTestCase
{
    /**
     * @test
     */
    public function execute_ok()
    {
        self::bootKernel();
        $application = new Application(self::$kernel);
        $application->add(new ValidateCommand());

        $installer = $this->getMockBuilder(InstallerService::class)
            ->disableOriginalConstructor()
            ->setMethods(['validate'])
            ->getMock();

        $installer->expects($this->once())
            ->method('validate');

        self::$kernel->getContainer()->set(InstallerService::class, $installer);

        $command = $application->find('yay:integration:validate');
        $commandTester = new CommandTester($command);

        $commandTester->execute(array(
            'command'  => $command->getName(),
            'name' => 'test',
            'path' => 'integration/test',
        ));

        $this->assertContains('[OK] Integration "test" valid', $commandTester->getDisplay());
    }


    /**
     * @test
     *
     */
    public function execute_warn()
    {
        self::bootKernel();
        $application = new Application(self::$kernel);
        $application->add(new ValidateCommand());

        $installer = $this->getMockBuilder(InstallerService::class)
            ->disableOriginalConstructor()
            ->setMethods(['validate'])
            ->getMock();

        $installer->expects($this->once())
            ->method('validate')
            ->will($this->throwException(new \Exception('Test exception')));

        self::$kernel->getContainer()->set(InstallerService::class, $installer);

        $command = $application->find('yay:integration:validate');
        $commandTester = new CommandTester($command);

        $commandTester->execute(array(
            'command'  => $command->getName(),
            'name' => 'test',
            'path' => 'integration/test',
        ));

        $this->assertContains('[WARNING] Integration "test" invalid', $commandTester->getDisplay());
        $this->assertContains('[ERROR] Test exception', $commandTester->getDisplay());
    }
}
