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

class IntegrationUninstallCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('yay:integration:uninstall')
            ->setDescription('Uninstall\'s an integration defined by it\'s path.')
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

        $this->uninstallServices($output, sprintf('%s/services.yml', $path));
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
    protected function uninstallServices(OutputInterface $output, string $sourceFilepath)
    {
        if (!file_exists($sourceFilepath)) {
            throw new \RuntimeException(sprintf('File %s is missing.', $sourceFilepath));
        }

        $targetFilepath = sprintf('%s/config/services/%s',
            $this->getContainer()->getParameter('kernel.root_dir'),
            $this->getServiceFilename($sourceFilepath)
        );

        $output->writeln('<info>Services: Removing symlink.</info>');
        if (file_exists($targetFilepath)) {
            (new Filesystem())->remove($targetFilepath);
            $output->writeln(sprintf('<info>- Removed Symlink <options=bold>%s</>.</info>', $targetFilepath));
        } else {
            $output->writeln('<info>- Skipping symlink removed. Already removed.</info>');
        }
    }
}
