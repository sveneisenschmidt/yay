<?php

namespace Yay\Bundle\IntegrationBundle\Command;

use Nelmio\Alice\Loader\NativeLoader;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Console\Helper\ProgressBar;
use Yay\Bundle\IntegrationBundle\Service\InstallerService;
use Yay\Component\Engine\Storage\DoctrineStorage;
use Yay\Component\Entity\Achievement\ActionDefinition;
use Yay\Component\Entity\Achievement\AchievementDefinition;
use Yay\Component\Entity\Achievement\Level;
use Yay\Component\Entity\Achievement\PersonalAction;
use Yay\Component\Entity\Player;

class EnableCommand extends ContainerAwareCommand
{
    /**
     * @return void
     */
    protected function configure(): void
    {
        $this->setName('yay:integration:enable')
            ->setDescription('Enables an integration.')
            ->addArgument(
                'name',
                InputArgument::REQUIRED,
                'The name of the integration to be enabled'
            )
            ->addArgument(
                'path',
                InputArgument::REQUIRED,
                'The path to all integration specific configuration files'
            );
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name = $input->getArgument('name');
        $path = $input->getArgument('path');

        $rootDirectory = $this->getContainer()->getParameter('kernel.root_dir');
        $sourceDirectory = realpath(sprintf('%s/../%s', $rootDirectory, $path));
        $targetDirectory = realpath(sprintf('%s/../app/config/integration', $rootDirectory));

        $installer = $this->getContainer()->get(InstallerService::class);
        $installer->install($name , $sourceDirectory, $targetDirectory);
    }
}
