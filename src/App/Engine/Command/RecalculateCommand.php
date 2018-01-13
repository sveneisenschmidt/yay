<?php

namespace App\Engine\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Component\Engine\Engine;

class RecalculateCommand extends ContainerAwareCommand
{
    protected function configure(): void
    {
        $this
            ->setName('yay:recalculate')
            ->setDescription('Recalculates a player\'s progress.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        /** @var Engine $engine */
        $engine = $this->getContainer()->get(Engine::class);
        foreach ($engine->findPlayerAny() as $player) {
            $achievements = $engine->advance($player);
        }

        if ($output->getFormatter()) {
            (new SymfonyStyle($input, $output))->success('Player progress recalculated');
        }
    }
}
