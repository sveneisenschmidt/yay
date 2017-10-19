<?php

namespace Yay\Bundle\EngineBundle\Command;

use Nelmio\Alice\Loader\NativeLoader;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Console\Helper\ProgressBar;
use Yay\Component\Entity\Achievement\ActionDefinition;
use Yay\Component\Entity\Achievement\AchievementDefinition;
use Yay\Component\Entity\Achievement\Level;
use Yay\Component\Entity\Achievement\PersonalAction;
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

    /**
     * @param $sourceFilepath
     *
     * @return string
     */
    protected function getServiceFilename($sourceFilepath): string
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

        $output->writeln('<info>Services: Copying file.</info>');

        if (!file_exists($targetFilepath)) {
            (new Filesystem())->copy($sourceFilepath, $targetFilepath, true);
            $output->writeln(sprintf('<info>- Created file <options=bold>%s</>:<options=bold>%s</>.</info>', $sourceFilepath, $targetFilepath));
        } else {
            $output->writeln('<info>- Skipping file creation. Already created.</info>');
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
        $objects = $loader->loadFile($sourceFilepath)->getObjects();

        $output->writeln('<info>Entities: Persisting entities.</info>');
        $progress = new ProgressBar($output, count($objects));

        foreach ($objects as $object) {
            $class = get_class($object);

            switch ($class) {
                case PersonalAction::class:
                    $criteria = [];
                    $always = true;

                    break;
                case ActionDefinition::class:
                case AchievementDefinition::class:
                case Level::class:
                    $criteria = ['name' => $object->getName()];
                    $always = false;

                    break;
                case Player::class:
                    $criteria = ['username' => $object->getUsername()];
                    $always = false;

                    break;
                default:
                    continue 2;
            }

            $entities = $manager->getRepository($class)->findBy($criteria);
            if (count($entities) < 1 || $always) {
                try {
                    $manager->persist($object);
                    $manager->flush();
                } catch (\Exception $e) {
                }
                $progress->advance(1);
            }
        }

        $output->writeln('');
    }
}
