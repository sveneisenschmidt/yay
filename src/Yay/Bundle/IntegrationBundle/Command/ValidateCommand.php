<?php

namespace Yay\Bundle\IntegrationBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Style\SymfonyStyle;
use Yay\Bundle\IntegrationBundle\Service\InstallerService;

class ValidateCommand extends ContainerAwareCommand
{
    protected function configure(): void
    {
        $this->setName('yay:integration:validate')
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
        $sourceFile = realpath(sprintf('%s/../%s.yml', $rootDirectory, $path));

        try {
            $installer = $this->getContainer()->get(InstallerService::class);
            $installer->validate($name, $sourceFile);

            (new SymfonyStyle($input, $output))->success(sprintf('Integration "%s" valid', $name));
        } catch (\Exception $e) {
            (new SymfonyStyle($input, $output))->warning(sprintf('Integration "%s" invalid', $name));
            (new SymfonyStyle($input, $output))->error($e->getMessage());
        }
    }
}
