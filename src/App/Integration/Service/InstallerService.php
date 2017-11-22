<?php

namespace App\Integration\Service;

use Nelmio\Alice\Loader\NativeLoader;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Yaml\Yaml;
use App\Integration\Configuration\ConfigurationTransformer;
use Component\Engine\StorageInterface;
use Component\Entity\Achievement\ActionDefinition;
use Component\Entity\Achievement\AchievementDefinition;
use Component\Entity\Achievement\Level;

class InstallerService
{
    const MODE_ALL = 0;
    const MODE_CONFIG = 1;
    const MODE_DATA = 2;

    protected $filesystem;

    protected $storage;

    protected $transformer;

    public function __construct(
        Filesystem $filesystem,
        StorageInterface $storage,
        ConfigurationTransformer $transformer
    ) {
        $this->filesystem = $filesystem;
        $this->storage = $storage;
        $this->transformer = $transformer;
    }

    public function install(
        string $name,
        string $sourceFile,
        string $targetDirectory,
        int $mode = self::MODE_ALL
    ): void {
        $config = $this->loadConfig($sourceFile);
        $configs = $this->transformFromConfig($config);

        if ($mode === self::MODE_ALL || $mode === self::MODE_CONFIG) {
            $this->installServices($configs['services.yml'], sprintf('%s/%s.yml', $targetDirectory, $name));
        }

        if ($mode === self::MODE_ALL || $mode === self::MODE_DATA) {
            $this->installEntities($configs['entities.yml']);
        }
    }

    public function transformFromConfig(array $config): array
    {
        return $this->transformer->transformFromUnprocessedConfig($config);
    }

    public function loadConfig(string $sourceFile): ?array
    {
        if (!$this->filesystem->exists($sourceFile)) {
            throw new \RuntimeException('Could not find source file.');
        }

        return Yaml::parse(file_get_contents($sourceFile));
    }

    public function loadEntities(array $data): array
    {
        return (new NativeLoader())->loadData($data)->getObjects();
    }

    /** @throws RuntimeException */
    public function installServices(array $data, string $targetFile): void
    {
        $contents = Yaml::dump($data, 32);
        $this->filesystem->dumpFile($targetFile, $contents);
    }

    public function installEntities(array $data): void
    {
        foreach ($this->loadEntities($data) as $object) {
            if ($object instanceof Level && !$this->storage->findLevel($object->getName())) {
                $this->storage->saveLevel($object);

                continue;
            }

            if ($object instanceof ActionDefinition && !$this->storage->findActionDefinition($object->getName())) {
                $this->storage->saveActionDefinition($object);

                continue;
            }

            if ($object instanceof AchievementDefinition && !$this->storage->findAchievementDefinition($object->getName())) {
                $this->storage->saveAchievementDefinition($object);

                continue;
            }
        }
    }

    public function uninstall(string $name, string $targetDirectory): void
    {
        $this->uninstallService(
            sprintf('%s/%s.yml', $targetDirectory, $name)
        );
    }

    /** @throws RuntimeException */
    public function uninstallService(string $targetFile): void
    {
        if ($this->filesystem->exists($targetFile)) {
            $this->filesystem->remove($targetFile);
        }
    }

    /** @throws \Exception */
    public function validate(
        string $name,
        string $sourceFile
    ): void {
        $config = $this->loadConfig($sourceFile);
        $configs = $this->transformFromConfig($config);
        $objects = $this->loadEntities($configs['entities.yml']);
    }
}
