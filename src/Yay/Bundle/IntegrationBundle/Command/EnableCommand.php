<?php

namespace Yay\Bundle\IntegrationBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Yay\Bundle\IntegrationBundle\Service\InstallerService;

class EnableCommand extends ContainerAwareCommand
{
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
        $installer->install($name, $sourceDirectory, $targetDirectory);
    }
}
