<?php

namespace App\Webhook\Test;

use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase as BaseWebTestCase;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Nelmio\Alice\Loader\NativeLoader;

abstract class WebTestCase extends BaseWebTestCase
{
    public function setUp(): void
    {
        $client = static::createClient();
        $container = $client->getContainer();

        /** @var KernelInterface $kernel */
        $kernel = $container->get('kernel');
        $directory = $kernel->locateResource('@Webhook');
        $fixture1 = sprintf('%s/Resources/fixtures/%s.yml', $directory, $this->getName());
        $fixture2 = sprintf('%s/Resources/fixtures/%s.yml', $directory, 'test_default');

        /** @var ManagerRegistry $doctrine */
        $doctrine = $container->get('doctrine');
        $manager = $doctrine ->getManager();

        $loader = new NativeLoader();
        $set = $loader->loadFile(file_exists($fixture1) ? $fixture1 : $fixture2);

        foreach ($set->getObjects() as $object) {
            $manager->persist($object);
        }

        $manager->flush();
    }

    public function tearDown(): void
    {
        $client = static::createClient();
        $container = $client->getContainer();

        /** @var ManagerRegistry $doctrine */
        $doctrine = $container->get('doctrine');
        $manager = $doctrine ->getManager();

        $purger = new ORMPurger($manager);
        $purger->purge();
    }

    public function assertArraySubsetHasKey(
        string $subsetKey,
        string $key,
        array $array,
        string $message = ''
    ): void {
        parent::assertArrayHasKey($subsetKey, $array, $message);
        $subset = $array[$subsetKey];
        parent::assertArrayHasKey($key, $subset, $message);
    }
}
