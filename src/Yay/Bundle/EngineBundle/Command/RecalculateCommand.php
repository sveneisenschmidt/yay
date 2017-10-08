<?php

namespace Yay\Bundle\EngineBundle\Command;

use Nelmio\Alice\Loader\NativeLoader;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Console\Helper\ProgressBar;

use Yay\Component\Entity\Achievement\ActionDefinition;
use Yay\Component\Entity\Achievement\AchievementDefinition;
use Yay\Component\Entity\Achievement\PersonalAction;
use Yay\Component\Entity\Player;

class RecalculateCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('yay:recalculate')
            ->setDescription('Install\'s an integration defined by it\'s path.')
        ;
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $engine = $this->getContainer()->get('Yay\Component\Engine\Engine');
        $players = $engine->findPlayerAny();

        $progress = new ProgressBar($output, $players->count());
        foreach ($players as $player) {
            $achievements = $engine->advance($player);
            $progress->advance(1);
        }

        $output->writeln('');
    }
}
