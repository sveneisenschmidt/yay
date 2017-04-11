<?php

namespace Yay\Bundle\EngineBundle\Command;

use Nelmio\Alice\Loader\NativeLoader;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Filesystem\Filesystem;

use Yay\Component\Entity\Achievement\ActionDefinition;
use Yay\Component\Entity\Achievement\AchievementDefinition;
use Yay\Component\Entity\Player;

class IntegrationInstallCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('yay:integration:install')
            ->setDescription('Install\'s an integration defined by it\'s path.')
            ->addArgument('path', InputArgument::REQUIRED, 'The path to all integration specific configuration files')
        ;
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $path = realpath(
            sprintf('%s/../%s',
                $this->getContainer()->getParameter('kernel.root_dir'),
                $input->getArgument('path')
            )
        );

        if (!file_exists($path) || !is_readable($path)) {
            throw new \InvalidArgumentException('Path is does not exist or is not readable.');
        }

        $this->installServices($output, sprintf('%s/services.yml', $path));
        $this->installEntities($output, sprintf('%s/entities.yml', $path));
    }

    protected function getServiceFilename($sourceFilepath)
    {
        $rootFilepath = $this->getContainer()->getParameter('kernel.root_dir');
        $filename = str_replace(realpath($rootFilepath.'/../'), '', $sourceFilepath);
        $filename = str_replace(DIRECTORY_SEPARATOR, '.', dirname($filename));
        $filename = sprintf('%s.yml', ltrim($filename, '.'));

        return $filename;

    }

    /**
     * @param OutputInterface $output
     * @param string          $sourceFilepath
     */
    protected function installServices(OutputInterface $output, string $sourceFilepath)
    {
        if (!file_exists($sourceFilepath)) {
            throw new \RuntimeException(sprintf('File %s is missing.', $sourceFilepath));
        }

        $targetFilepath = sprintf('%s/config/services/%s',
            $this->getContainer()->getParameter('kernel.root_dir'),
            $this->getServiceFilename($sourceFilepath)
        );

        $output->writeln('<info>Services: Creating symlink.</info>');

        if (!file_exists($targetFilepath)) {
            (new Filesystem())->symlink($sourceFilepath, $targetFilepath);
            $output->writeln(sprintf('<info>- Created Symlink <options=bold>%s</>:<options=bold>%s</>.</info>', $sourceFilepath, $targetFilepath));
        } else {
            $output->writeln('<info>- Skipping symlink creation. Already created.</info>');
        }

    }

    /**
     * @param OutputInterface $output
     * @param string          $sourceFilepath
     */
    protected function installEntities(OutputInterface $output, string $sourceFilepath)
    {
        if (!file_exists($sourceFilepath)) {
            throw new \RuntimeException(sprintf('File %s is missing.', $sourceFilepath));
        }

        $manager = $this->getContainer()->get('doctrine')->getManager();
        $loader = new NativeLoader();
        $set = $loader->loadFile($sourceFilepath);

        $output->writeln('<info>Entities: Persisting entities.</info>');

        foreach ($set->getObjects() as $object) {
            if ($object instanceof ActionDefinition) {
                /** @var ActionDefinition $object */
                $actionDefinitions = $manager->getRepository(ActionDefinition::class)
                    ->findBy(['name' => $object->getName()]);

                if (count($actionDefinitions) > 0) {
                    $output->writeln(sprintf('<info>- Skipping ActionDefinition <options=bold>%s</>. Already installed.</info>', $object->getName()));
                    continue;
                }

                $manager->persist($object);
                $manager->flush();

                $output->writeln(sprintf('<info>- Installed ActionDefinition <options=bold>%s</>.</info>', $object->getName()));
                continue;
            } else
            if ($object instanceof AchievementDefinition) {
                /** @var AchievementDefinition $object */
                $achievementDefinitions = $manager->getRepository(AchievementDefinition::class)
                    ->findBy(['name' => $object->getName()]);

                if (count($achievementDefinitions) > 0) {
                    $output->writeln(sprintf('<info>- Skipping AchievementDefinition <options=bold>%s</>. Already installed.</info>', $object->getName()));
                    continue;
                }

                $manager->persist($object);
                $manager->flush();

                $output->writeln(sprintf('<info>- Installed AchievementDefinition <options=bold>%s</>.</info>', $object->getName()));
                continue;
            } else
            if ($object instanceof Player) {
                /** @var AchievementDefinition $object */
                $players = $manager->getRepository(Player::class)
                    ->findBy(['username' => $object->getUsername()]);

                if (count($players) > 0) {
                    $output->writeln(sprintf('<info>- Skipping Player <options=bold>%s</>. Already installed.</info>', $object->getName()));
                    continue;
                }

                $manager->persist($object);
                $manager->flush();

                $output->writeln(sprintf('<info>- Installed Player <options=bold>%s</>.</info>', $object->getUsername()));
                continue;
            }

            throw new \RuntimeException(sprintf('Unsupported entity of class %s.', get_class($object)));

        }
    }
}
