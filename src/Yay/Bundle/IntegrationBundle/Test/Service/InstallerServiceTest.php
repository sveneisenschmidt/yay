<?php

namespace Yay\Bundle\IntegrationBundle\Test\Service;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Yaml\Yaml;
use Yay\Bundle\IntegrationBundle\Configuration\ConfigurationTransformer;
use Yay\Bundle\IntegrationBundle\Service\InstallerService;
use Yay\Component\Engine\StorageInterface;

class InstallerServiceTest extends TestCase
{
    /**
     * @test
     */
    public function install()
    {
        $installer = $this->getMockBuilder(InstallerService::class)
            ->disableOriginalConstructor()
            ->setMethods(['installServices', 'installEntities', 'loadConfig', 'transformFromConfig'])
            ->getMock();

        $installer->expects($this->atLeastonce())
            ->method('installServices');

        $installer->expects($this->atLeastonce())
            ->method('installEntities');

        $installer->expects($this->atLeastonce())
            ->method('loadConfig')
            ->willReturn([]);

        $installer->expects($this->atLeastonce())
            ->method('transformFromConfig')
            ->willReturn(['services.yml' => [], 'entities.yml' => []]);

        $installer->install('a', 'b', 'c');
    }

    /**
     * @test
     */
    public function install_service()
    {
        $storage = $this->getMockBuilder(StorageInterface::class)
            ->getMock();

        $filesystem = $this->getMockBuilder(Filesystem::class)
            ->setMethods(['dumpFile'])
            ->getMock();

        $filesystem->expects($this->atLeastonce())
            ->method('dumpFile');

        $transformer = $this->getMockBuilder(ConfigurationTransformer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $sourceFile = [];
        $targetFile = sprintf('%s/%s.yml', sys_get_temp_dir(), __FUNCTION__);

        $installer = new InstallerService($filesystem, $storage, $transformer);
        $installer->installServices($sourceFile, $targetFile);
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

        $transformer = $this->getMockBuilder(ConfigurationTransformer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $targetFile = sprintf('%s/%s.yml', sys_get_temp_dir(), __FUNCTION__);
        $installer = new InstallerService($filesystem, $storage, $transformer);
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

        $transformer = $this->getMockBuilder(ConfigurationTransformer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $installer = new InstallerService($filesystem, $storage, $transformer);
        $installer->uninstall(__FUNCTION__, sys_get_temp_dir());
    }

    /**
     * @test
     */
    public function load_entities(): void
    {
        $storage = $this->getMockBuilder(StorageInterface::class)
            ->getMock();

        $transformer = $this->getMockBuilder(ConfigurationTransformer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $installer = new InstallerService(new Filesystem(), $storage, $transformer);

        $file = sprintf('%s/Fixture/%s.yml', __DIR__, __FUNCTION__);
        $config = $installer->loadConfig($file);
        $this->assertNotEmpty($config);

        $objects = $installer->loadEntities($config);
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

        $transformer = $this->getMockBuilder(ConfigurationTransformer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $file = sprintf('%s/Fixture/%s.yml', __DIR__, __FUNCTION__);
        $objects = Yaml::parse(file_get_contents($file));

        $installer = new InstallerService(new Filesystem(), $storage, $transformer);
        $installer->installEntities($objects);
    }

    /**
     * @test
     */
    public function install_entities_supports_action()
    {
        $storage = $this->getMockBuilder(StorageInterface::class)
            ->setMethods(get_class_methods(StorageInterface::class))
            ->getMock();

        $storage->expects($this->exactly(2))
            ->method('findActionDefinition');

        $storage->expects($this->exactly(2))
            ->method('saveActionDefinition');

        $transformer = $this->getMockBuilder(ConfigurationTransformer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $file = sprintf('%s/Fixture/%s.yml', __DIR__, __FUNCTION__);
        $objects = Yaml::parse(file_get_contents($file));

        $installer = new InstallerService(new Filesystem(), $storage, $transformer);
        $installer->installEntities($objects);
    }

    /**
     * @test
     */
    public function install_entities_supports_achievement()
    {
        $storage = $this->getMockBuilder(StorageInterface::class)
            ->setMethods(get_class_methods(StorageInterface::class))
            ->getMock();

        $storage->expects($this->exactly(2))
            ->method('findAchievementDefinition');

        $storage->expects($this->exactly(2))
            ->method('saveAchievementDefinition');

        $transformer = $this->getMockBuilder(ConfigurationTransformer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $file = sprintf('%s/Fixture/%s.yml', __DIR__, __FUNCTION__);
        $objects = Yaml::parse(file_get_contents($file));

        $installer = new InstallerService(new Filesystem(), $storage, $transformer);
        $installer->installEntities($objects);
    }
}
