<?php

namespace Component\Engine\Tests\EventListener;

use PHPUnit\Framework\TestCase;
use Component\Engine\Storage\StorageInterface;
use Component\Engine\Event\ObjectEvent;
use Component\Engine\EventListener\ActivityListener;
use Component\Entity\PlayerInterface;
use Component\Entity\Achievement\AchievementDefinitionInterface;
use Component\Entity\Achievement\ActionDefinitionInterface;
use Component\Entity\Achievement\PersonalActionInterface;
use Component\Entity\Achievement\PersonalAchievementInterface;

class ActivityListenerTest extends TestCase
{
    public function test_on_create_player(): void
    {
        $faker = \Faker\Factory::create();

        $storage = $this->createMock(StorageInterface::class);

        $storage->expects($this->once())
            ->method('saveActivity');

        $player = $this->createConfiguredMock(PlayerInterface::class, [
            'getUsername' => $faker->userName,
        ]);

        $event = new ObjectEvent($player);
        $listener = new ActivityListener($storage);
        $listener->onCreatePlayer($event);
    }

    public function test_on_grant_personal_action(): void
    {
        $faker = \Faker\Factory::create();

        $storage = $this->createMock(StorageInterface::class);

        $storage->expects($this->once())
            ->method('saveActivity');

        $personalAction = $this->createConfiguredMock(PersonalActionInterface::class, [
            'getPlayer' => $this->createConfiguredMock(PlayerInterface::class, [
                'getUsername' => $faker->userName,
            ]),
            'getActionDefinition' => $this->createConfiguredMock(ActionDefinitionInterface::class, [
                'getName' => $faker->text,
            ]),
            'getAchievedAt' => new \DateTime(),
        ]);

        $event = new ObjectEvent($personalAction);
        $listener = new ActivityListener($storage);
        $listener->onGrantPersonalAction($event);
    }

    public function test_on_grant_personal_achievement(): void
    {
        $faker = \Faker\Factory::create();

        $storage = $this->createMock(StorageInterface::class);

        $storage->expects($this->once())
            ->method('saveActivity');

        $personalAchievement = $this->createConfiguredMock(PersonalAchievementInterface::class, [
            'getPlayer' => $this->createConfiguredMock(PlayerInterface::class, [
                'getUsername' => $faker->userName,
            ]),
            'getAchievementDefinition' => $this->createConfiguredMock(AchievementDefinitionInterface::class, [
                'getName' => $faker->text,
            ]),
            'getAchievedAt' => new \DateTime(),
        ]);

        $event = new ObjectEvent($personalAchievement);
        $listener = new ActivityListener($storage);
        $listener->onGrantPersonalAchievement($event);
    }

    public function test_on_level_changed(): void
    {
        $faker = \Faker\Factory::create();

        $storage = $this->createMock(StorageInterface::class);

        $storage->expects($this->once())
            ->method('saveActivity');

        $player = $this->createConfiguredMock(PlayerInterface::class, [
            'getUsername' => $faker->userName,
            'getLevel' => rand(1, 100)
        ]);

        $event = new ObjectEvent($player);
        $listener = new ActivityListener($storage);
        $listener->onLevelChanged($event);
    }

    public function test_on_score_changed(): void
    {
        $faker = \Faker\Factory::create();

        $storage = $this->createMock(StorageInterface::class);

        $storage->expects($this->once())
            ->method('saveActivity');

        $player = $this->createConfiguredMock(PlayerInterface::class, [
            'getUsername' => $faker->userName,
            'getScore' => rand(1, 100)
        ]);

        $event = new ObjectEvent($player);
        $listener = new ActivityListener($storage);
        $listener->onScoreChanged($event);
    }
}
