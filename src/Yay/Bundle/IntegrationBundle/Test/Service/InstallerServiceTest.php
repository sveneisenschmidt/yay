<?php

namespace Yay\Bundle\IntegrationBundle\Test\Service;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Filesystem\Filesystem;
use Yay\Bundle\IntegrationBundle\Service\InstallerService;
use Yay\Component\Engine\StorageInterface;
use Yay\Component\Entity\Achievement\LevelInterface;

class InstallerServiceTest extends TestCase
{
    /**
     * @test
     */
    public function install()
    {
        $installer = $this->getMockBuilder(InstallerService::class)
            ->disableOriginalConstructor()
            ->setMethods(['installServices', 'installEntities'])
            ->getMock();

        $installer->expects($this->atLeastonce())
            ->method('installServices');

        $installer->expects($this->atLeastonce())
            ->method('installEntities');

        $installer->install('a', 'b', 'c');
    }

    /**
     * @test
     */
    public function uninstall()
    {
        $installer = $this->getMockBuilder(InstallerService::class)
            ->disableOriginalConstructor()
            ->setMethods(['uninstallService'])
            ->getMock();

        $installer->expects($this->atLeastonce())
            ->method('uninstallService');

        $installer->uninstall('a', 'b');
    }

    /**
     * @test
     */
    public function uninstall_service()
    {
        $storage = $this->getMockBuilder(StorageInterface::class)
            ->getMock();

        $filesystem = $this->getMockBuilder(Filesystem::class)
            ->setMethods(['remove', 'exists'])
            ->getMock();

        $filesystem->expects($this->atLeastonce())
            ->method('exists')
            ->willReturn(true);

        $filesystem->expects($this->atLeastonce())
            ->method('remove');

        $targetFile = sprintf('%s/%s.yml', sys_get_temp_dir(), __FUNCTION__);
        $installer = new InstallerService($filesystem, $storage);
        $installer->uninstallService($targetFile);
    }

    /**
     * @test
     */
    public function uninstall_service_not_exists()
    {
        $storage = $this->getMockBuilder(StorageInterface::class)
            ->getMock();

        $filesystem = $this->getMockBuilder(Filesystem::class)
            ->setMethods(['remove', 'exists'])
            ->getMock();

        $filesystem->expects($this->atLeastonce())
            ->method('exists')
            ->willReturn(false);

        $filesystem->expects($this->never())
            ->method('remove');

        $installer = new InstallerService($filesystem, $storage);
        $installer->uninstall(__FUNCTION__, sys_get_temp_dir());
    }

    /**
     * @test
     */
    public function load_entities(): void
    {
        $storage = $this->getMockBuilder(StorageInterface::class)->getMock();
        $installer = new InstallerService(new Filesystem(), $storage);

        $file = sprintf('%s/Fixture/%s.yml', __DIR__, __FUNCTION__);
        $objects = $installer->loadEntities($file);
        $this->assertCount(5, $objects);
    }

    /**
     * @test
     */
    public function install_entities_supports_level()
    {
        $storage = $this->getMockBuilder(StorageInterface::class)
            ->setMethods(get_class_methods(StorageInterface::class))
            ->getMock();

        $storage->expects($this->exactly(5))
            ->method('findLevel');

        $storage->expects($this->exactly(5))
            ->method('saveLevel');

        $installer = new InstallerService(new Filesystem(), $storage);

        $file = sprintf('%s/Fixture/%s.yml', __DIR__, __FUNCTION__);
        $installer->installEntities($file);
    }
}
