<?php

namespace Bundle\Engine\Tests\Command;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;
use App\Integration\Command\EnableCommand;
use App\Integration\Service\InstallerService;

class EnableCommandTest extends KernelTestCase
{
    public function test_execute(): void
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

        $this->assertContains('[OK] Integration "test" enabled. (Mode: MODE_ALL)', $commandTester->getDisplay());
    }

    public function test_execute_flag_config_only(): void
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

        $commandTester->execute([
            'command' => $command->getName(),
            'name' => 'test',
            'path' => 'integration/test',
            '--config-only' => '1',
        ]);

        $this->assertContains('[OK] Integration "test" enabled. (Mode: MODE_CONFIG)', $commandTester->getDisplay());
    }

    public function test_execute_flag_data_only(): void
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

        $commandTester->execute([
            'command' => $command->getName(),
            'name' => 'test',
            'path' => 'integration/test',
            '--data-only' => '1',
        ]);

        $this->assertContains('[OK] Integration "test" enabled. (Mode: MODE_DATA)', $commandTester->getDisplay());
    }
}
