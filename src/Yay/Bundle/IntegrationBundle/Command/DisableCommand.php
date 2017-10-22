<?php

namespace Yay\Bundle\IntegrationBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Style\SymfonyStyle;
use Yay\Bundle\IntegrationBundle\Service\InstallerService;

class DisableCommand extends ContainerAwareCommand
{
    protected function configure(): void
    {
        $this->setName('yay:integration:disable')
            ->setDescription('Disables an integration.')
            ->addArgument(
                'name',
                InputArgument::REQUIRED,
                'The name of the integration to be disabled'
            );
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name = $input->getArgument('name');

        $rootDirectory = $this->getContainer()->getParameter('kernel.root_dir');
        $targetDirectory = realpath(sprintf('%s/../app/config/integration', $rootDirectory));

        $installer = $this->getContainer()->get(InstallerService::class);
        $installer->uninstall($name, $targetDirectory);

        (new SymfonyStyle($input, $output))->success(sprintf('Integration "%s" disabled', $name));
    }
}
