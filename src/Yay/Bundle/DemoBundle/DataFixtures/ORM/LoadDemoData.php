<?php

namespace Yay\Bundle\DemoBundle\DataFixtures\ORM;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Nelmio\Alice\Loader\NativeLoader;

use Yay\Component\Engine\Engine;

class LoadDemoData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @param ContainerInterface|null $container
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $this->loadFixtures($manager);
        $this->calculateAchievements($manager);

    }

    /**
     * @param ObjectManager $manager
     */
    public function loadFixtures(ObjectManager $manager)
    {
        $loader = new NativeLoader();
        $set = $loader->loadFile(__DIR__.'/../../Resources/fixtures/demo.yml');

        foreach ($set->getObjects() as $object) {
            $manager->persist($object);
        }

        $manager->flush();
    }

    /**
     * @param ObjectManager $manager
     */
    public function calculateAchievements(ObjectManager $manager)
    {
        /** @var Engine $engine */
        $engine = $this->container->get('yay.engine');
        $players = $engine->getStorage()->findPlayerBy([]);

        foreach ($players as $player) {
            $manager->refresh($player);
            $achievements = $engine->advance($player);
            print sprintf('  > Player %s:%s', $player->getUsername(), PHP_EOL);
            if (count($achievements) > 0) {
                foreach ($achievements as $achievement) {
                    print sprintf('   - Achievement %s granted.%s', $achievement->getGoalDefinition()->getName(), PHP_EOL);
                }
            } else {
                print sprintf('   - No achievements granted.%s', PHP_EOL);
            }
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getOrder(): int
    {
        return 1;
    }
}
