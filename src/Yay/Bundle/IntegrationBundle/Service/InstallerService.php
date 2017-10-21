<?php

namespace Yay\Bundle\IntegrationBundle\Service;

use Nelmio\Alice\Loader\NativeLoader;
use Symfony\Component\Filesystem\Filesystem;
use Yay\Component\Engine\StorageInterface;
use Yay\Component\Entity\Achievement\ActionDefinition;
use Yay\Component\Entity\Achievement\AchievementDefinition;
use Yay\Component\Entity\Achievement\Level;

class InstallerService
{
    /**
     * @param Filesystem
     */
    protected $filesystem;

    /**
     * @param StorageInterface
     */
    protected $storage;

    /**
     * @param Filesystem       $filesystem
     * @param StorageInterface $storage
     */
    public function __construct(
        Filesystem $filesystem,
        StorageInterface $storage
    ) {
        $this->filesystem = $filesystem;
        $this->storage = $storage;
    }

    /**
     * @param string $name
     * @param string $sourceDirectory
     * @param string $targetDirectory
     */
    public function install(
        string $name,
        string $sourceDirectory,
        string $targetDirectory
    ): void {
        $this->installServices(
            sprintf('%s/services.yml', $sourceDirectory),
            sprintf('%s/%s.yml', $targetDirectory, $name)
        );

        $this->installEntities(
            sprintf('%s/entities.yml', $sourceDirectory)
        );
    }

    /**
     * @param string $sourceFile
     * @param string $targetFile
     *
     * @throws RuntimeException
     */
    public function installServices(string $sourceFile, string $targetFile): void
    {
        if (!$this->filesystem->exists($sourceFile)) {
            throw new \RuntimeException(sprintf('File %s is missing.', $sourceFile));
        }

        $this->filesystem->copy($sourceFile, $targetFile);
    }

    /**
     * @param string $file
     *
     * @return array
     */
    public function loadEntities(string $file): array
    {
        return (new NativeLoader())->loadFile($file)->getObjects();
    }

    /**
     * @param string $sourceFile
     */
    public function installEntities(string $sourceFile): void
    {
        if (!$this->filesystem->exists($sourceFile)) {
            throw new \RuntimeException(sprintf('File %s is missing.', $sourceFile));
        }

        foreach ($this->loadEntities($sourceFile) as $object) {
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

    /**
     * @param string $name
     * @param string $targetDirectory
     */
    public function uninstall(string $name, string $targetDirectory): void
    {
        $this->uninstallService(
            sprintf('%s/%s.yml', $targetDirectory, $name)
        );
    }

    /**
     * @param string $targetFile
     *
     * @throws RuntimeException
     */
    public function uninstallService(string $targetFile): void
    {
        if ($this->filesystem->exists($targetFile)) {
            $this->filesystem->remove($targetFile);
        }
    }
}
