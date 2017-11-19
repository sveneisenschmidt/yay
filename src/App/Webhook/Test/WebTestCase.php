<?php

namespace App\Webhook\Test;

use ArrayAccess;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase as BaseWebTestCase;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Nelmio\Alice\Loader\NativeLoader;

abstract class WebTestCase extends BaseWebTestCase
{
    /**
     * @setup
     */
    public function setUp()
    {
        $client = static::createClient();
        $container = $client->getContainer();

        $directory = $container->get('kernel')->locateResource('@Webhook');
        $fixture1 = sprintf('%s/Resources/fixtures/Test.%s.yml', $directory, $this->getName());
        $fixture2 = sprintf('%s/Resources/fixtures/Test.%s.yml', $directory, 'Default');

        $manager = $container
            ->get('doctrine')
            ->getManager();

        $loader = new NativeLoader();
        $set = $loader->loadFile(file_exists($fixture1) ? $fixture1 : $fixture2);

        foreach ($set->getObjects() as $object) {
            $manager->persist($object);
        }

        $manager->flush();
    }

    /**
     * @teardown
     */
    public function tearDown()
    {
        $manager = static::createClient()
            ->getContainer()
            ->get('doctrine')
            ->getManager();

        $purger = new ORMPurger($manager);
        $purger->purge();
    }

    /**
     * Asserts that an array subset has a specified key.
     *
     * @param mixed             $subsetKey
     * @param mixed             $key
     * @param array|ArrayAccess $array
     * @param string            $message
     */
    public function assertArraySubsetHasKey($subsetKey, $key, $array, $message = '')
    {
        parent::assertArrayHasKey($subsetKey, $array, $message = '');
        $subset = $array[$subsetKey];
        parent::assertArrayHasKey($key, $subset, $message = '');
    }
}
