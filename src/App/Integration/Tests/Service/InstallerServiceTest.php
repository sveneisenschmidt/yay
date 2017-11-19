<?php

namespace App\Integration\Tests\Service;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Yaml\Yaml;
use App\Integration\Configuration\ConfigurationTransformer;
use App\Integration\Service\InstallerService;
use Component\Engine\StorageInterface;

class InstallerServiceTest extends TestCase
{
    public function test_install(): void
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

    public function test_install_service(): void
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

    /* @expectedException \RuntimeException */
    public function test_load_config(): void
    {
        $storage = $this->getMockBuilder(StorageInterface::class)
            ->getMock();

        $filesystem = $this->getMockBuilder(Filesystem::class)
            ->setMethods(['exists'])
            ->getMock();

        $filesystem->expects($this->atLeastonce())
            ->method('exists')
            ->willReturn(false);

        $transformer = $this->getMockBuilder(ConfigurationTransformer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $installer = new InstallerService($filesystem, $storage, $transformer);
        $installer->loadConfig('');
    }

    public function test_transform_from_config(): void
    {
        $storage = $this->getMockBuilder(StorageInterface::class)
            ->getMock();

        $filesystem = $this->getMockBuilder(Filesystem::class)
            ->getMock();

        $transformer = $this->getMockBuilder(ConfigurationTransformer::class)
            ->disableOriginalConstructor()
            ->setMethods(['transformFromUnprocessedConfig'])
            ->getMock();

        $transformer->expects($this->atLeastonce())
            ->method('transformFromUnprocessedConfig')
            ->willReturn([]);

        $installer = new InstallerService($filesystem, $storage, $transformer);
        $installer->transformFromConfig([]);
    }

    public function test_uninstall(): void
    {
        $installer = $this->getMockBuilder(InstallerService::class)
            ->disableOriginalConstructor()
            ->setMethods(['uninstallService'])
            ->getMock();

        $installer->expects($this->atLeastonce())
            ->method('uninstallService');

        $installer->uninstall('a', 'b');
    }

    public function test_uninstall_service(): void
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

    public function test_uninstall_service_not_exists(): void
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

    public function test_load_entities(): void
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

    public function test_install_entities_supports_level(): void
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

    public function test_install_entities_supports_action(): void
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

    public function test_install_entities_supports_achievement(): void
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

    public function test_validate(): void
    {
        $installer = $this->getMockBuilder(InstallerService::class)
            ->disableOriginalConstructor()
            ->setMethods(['loadConfig', 'transformFromConfig', 'loadEntities'])
            ->getMock();

        $installer->expects($this->atLeastonce())
            ->method('loadConfig')
            ->willReturn([]);

        $installer->expects($this->atLeastonce())
            ->method('transformFromConfig')
            ->willReturn(['services.yml' => [], 'entities.yml' => []]);

        $installer->expects($this->atLeastonce())
            ->method('loadEntities')
            ->willReturn([]);

        $installer->validate('a', 'b');
    }
}
