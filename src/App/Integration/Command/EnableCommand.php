<?php

namespace App\Integration\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Style\SymfonyStyle;
use App\Integration\Service\InstallerService;

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
            )
            ->addOption(
                'config-only',
                null,
                InputOption::VALUE_NONE,
                'Import only configuration, no database actions will be executed'
            )
            ->addOption(
                'data-only',
                null,
                InputOption::VALUE_NONE,
                'Import only database fixtures, no filesystem actions will be executed'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name = $input->getArgument('name');
        $path = $input->getArgument('path');

        if ($input->getOption('config-only')) {
            $mode = InstallerService::MODE_CONFIG;
        } elseif ($input->getOption('data-only')) {
            $mode = InstallerService::MODE_DATA;
        } else {
            $mode = InstallerService::MODE_ALL;
        }

        $rootDirectory = $this->getContainer()->getParameter('kernel.root_dir');
        $sourceFile = realpath(sprintf('%s/../../%s.yml', $rootDirectory, $path));
        $targetDirectory = realpath(sprintf('%s/../../config/integration', $rootDirectory));

        /** @var InstallerService $installer */
        $installer = $this->getContainer()->get(InstallerService::class);
        $installer->install($name, $sourceFile, $targetDirectory, $mode);

        (new SymfonyStyle($input, $output))->success(
            sprintf(
                'Integration "%s" enabled. (Mode: %s)',
                $name,
                (InstallerService::MODE_DATA == $mode) ? 'MODE_DATA' :
                    ((InstallerService::MODE_CONFIG == $mode) ? 'MODE_CONFIG' : 'MODE_ALL')
            )
        );
    }
}
