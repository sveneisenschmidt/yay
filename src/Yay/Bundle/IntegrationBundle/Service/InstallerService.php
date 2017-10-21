<?php

namespace Yay\Bundle\IntegrationBundle\Service;

use Nelmio\Alice\Loader\NativeLoader;
use Symfony\Component\Filesystem\Filesystem;
use Yay\Component\Engine\StorageInterface;
use Yay\Component\Engine\Storage\DoctrineStorage;
use Yay\Component\Entity\Achievement\ActionDefinition;
use Yay\Component\Entity\Achievement\AchievementDefinition;
use Yay\Component\Entity\Achievement\Level;
use Yay\Component\Entity\Achievement\PersonalAction;
use Yay\Component\Entity\Player;

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
     * @param DoctrineStorage $storage
     */
    public function __construct(
        Filesystem $filesystem,
        DoctrineStorage $storage
    ) {
        $this->filesystem = $filesystem;
        $this->storage = $storage;
    }
    /**
     * @param string $integrationName
     * @param string $integrationSourceDirectory
     * @param string $integrationTargetDirectory
     */
    public function install(
        string $integrationName,
        string $integrationSourceDirectory,
        string $integrationTargetDirectory
    ): void {
        $this->installServices($integrationName, $integrationSourceDirectory, $integrationTargetDirectory);
        $this->installEntities($integrationName, $integrationSourceDirectory, $integrationTargetDirectory);
    }

    /**
     * @param string $integrationName
     * @param string $integrationSourceDirectory
     * @param string $integrationTargetDirectory
     * @throws RuntimeException
     */
    protected function installServices(
        string $integrationName,
        string $integrationSourceDirectory,
        string $integrationTargetDirectory
    ): void {
        $sourceFilepath = sprintf('%s/services.yml', $integrationSourceDirectory);
        $targetFilepath = sprintf('%s/%s.yml', $integrationTargetDirectory, $integrationName);

        if (!file_exists($sourceFilepath)) {
            throw new \RuntimeException(sprintf('File %s is missing.', $sourceFilepath));
        }

        $this->filesystem->copy($sourceFilepath, $targetFilepath);
    }

    /**
     * @param string $integrationName
     * @param string $integrationSourceDirectory
     * @param string $integrationTargetDirectory
     */
    protected function installEntities(
        string $integrationName,
        string $integrationSourceDirectory,
        string $integrationTargetDirectory
    ): void {
        $sourceFilepath = sprintf('%s/entities.yml', $integrationSourceDirectory);

        if (!file_exists($sourceFilepath)) {
            throw new \RuntimeException(sprintf('File %s is missing.', $sourceFilepath));
        }

        $loader = new NativeLoader();
        $objects = $loader->loadFile($sourceFilepath)->getObjects();

        foreach ($objects as $object) {
            $class = get_class($object);

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
}
