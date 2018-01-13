<?php

namespace Component\Engine\Tests\Storage;

use PHPUnit\Framework\TestCase;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Component\Engine\Storage\DoctrineStorage;
use Component\Engine\Storage\StorageTrait;
use Component\Entity\Achievement\ActionDefinition;
use Component\Entity\Achievement\ActionDefinitionCollection;
use Component\Entity\Achievement\AchievementDefinition;
use Component\Entity\Achievement\AchievementDefinitionCollection;
use Component\Entity\Achievement\PersonalAchievement;
use Component\Entity\Achievement\PersonalAction;
use Component\Entity\Achievement\Level;
use Component\Entity\Achievement\LevelCollection;
use Component\Entity\Player;
use Component\Entity\PlayerCollection;
use Component\Entity\Activity;
use Component\Entity\ActivityCollection;

class DoctrineStorageTest extends TestCase
{
    public function wrapStorage(DoctrineStorage $storage): object
    {
        return new class($storage) {
            use StorageTrait;

            public function __construct(DoctrineStorage $storage)
            {
                $this->setStorage($storage);
            }
        };
    }

    public function createManagerMockSave(): EntityManagerInterface
    {
        $manager = $this->getMockBuilder(EntityManagerInterface::class)
            ->disableOriginalConstructor()
            ->setMethods(get_class_methods(EntityManagerInterface::class))
            ->getMock();

        $manager->expects($this->once())
            ->method('persist');

        $manager->expects($this->once())
            ->method('flush');

        return $manager;
    }

    public function createManagerMockRefresh(): EntityManagerInterface
    {
        $manager = $this->getMockBuilder(EntityManagerInterface::class)
            ->disableOriginalConstructor()
            ->setMethods(get_class_methods(EntityManagerInterface::class))
            ->getMock();

        $manager->expects($this->once())
            ->method('refresh');

        return $manager;
    }

    public function createManagerMockOne(string $class, bool $empty = false): EntityManagerInterface
    {
        $repository = $this->getMockBuilder(ObjectRepository::class)
            ->disableOriginalConstructor()
            ->setMethods(get_class_methods(ObjectRepository::class))
            ->getMock();

        $repository->expects($this->once())
            ->method('find')
            ->willReturn(!$empty ? $this->createMock($class) : null);

        $manager = $this->getMockBuilder(EntityManagerInterface::class)
            ->disableOriginalConstructor()
            ->setMethods(get_class_methods(EntityManagerInterface::class))
            ->getMock();

        $manager->expects($this->once())
            ->method('getRepository')
            ->willReturn($repository);

        return $manager;
    }

    public function createManagerMockMany(string $class, bool $empty = false): EntityManagerInterface
    {
        $repository = $this->getMockBuilder(ObjectRepository::class)
            ->disableOriginalConstructor()
            ->setMethods(get_class_methods(ObjectRepository::class))
            ->getMock();

        $repository->expects($this->once())
            ->method('findBy')
            ->willReturn(!$empty ? [$this->createMock($class)] : []);

        $manager = $this->getMockBuilder(EntityManagerInterface::class)
            ->disableOriginalConstructor()
            ->setMethods(get_class_methods(EntityManagerInterface::class))
            ->getMock();

        $manager->expects($this->once())
            ->method('getRepository')
            ->willReturn($repository);

        return $manager;
    }

    public function test_find_player(): void
    {
        $manager = $this->createManagerMockOne(Player::class);
        /** @var object&StorageTrait $storage */
        $storage = $this->wrapStorage(new DoctrineStorage($manager));
        $object = $storage->findPlayer($primaryKey = rand(1, 100));
        $this->assertInstanceOf(Player::class, $object);
    }

    public function test_find_player_empty(): void
    {
        $manager = $this->createManagerMockOne(Player::class, true);
        /** @var object&StorageTrait $storage */
        $storage = $this->wrapStorage(new DoctrineStorage($manager));
        $object = $storage->findPlayer($primaryKey = rand(1, 100));
        $this->assertNull($object);
    }

    public function test_find_player_by(): void
    {
        $manager = $this->createManagerMockMany(Player::class);
        /** @var object&StorageTrait $storage */
        $storage = $this->wrapStorage(new DoctrineStorage($manager));
        $objects = $storage->findPlayerBy([]);
        $this->assertInstanceOf(PlayerCollection::class, $objects);
        $this->assertGreaterThan(0, count($objects));
    }

    public function test_find_player_by_empty(): void
    {
        $manager = $this->createManagerMockMany(Player::class, true);
        /** @var object&StorageTrait $storage */
        $storage = $this->wrapStorage(new DoctrineStorage($manager));
        $objects = $storage->findPlayerBy([]);
        $this->assertInstanceOf(PlayerCollection::class, $objects);
        $this->assertEquals(0, count($objects));
    }

    public function test_find_achievement_definition(): void
    {
        $manager = $this->createManagerMockOne(AchievementDefinition::class);
        $object = (new DoctrineStorage($manager))->findAchievementDefinition($primaryKey = rand(1, 100));
        $this->assertInstanceOf(AchievementDefinition::class, $object);
    }

    public function test_find_achievement_definition_empty(): void
    {
        $manager = $this->createManagerMockOne(AchievementDefinition::class, true);
        $object = (new DoctrineStorage($manager))->findAchievementDefinition($primaryKey = rand(1, 100));
        $this->assertNull($object);
    }

    public function test_find_achievement_definition_by(): void
    {
        $manager = $this->createManagerMockMany(AchievementDefinition::class);
        /** @var object&StorageTrait $storage */
        $storage = $this->wrapStorage(new DoctrineStorage($manager));
        $objects = $storage->findAchievementDefinitionBy([]);
        $this->assertInstanceOf(AchievementDefinitionCollection::class, $objects);
        $this->assertGreaterThan(0, count($objects));
    }

    public function test_find_achievement_definition_by_empty(): void
    {
        $manager = $this->createManagerMockMany(AchievementDefinition::class, true);
        /** @var object&StorageTrait $storage */
        $storage = $this->wrapStorage(new DoctrineStorage($manager));
        $objects = $storage->findAchievementDefinitionBy([]);
        $this->assertInstanceOf(AchievementDefinitionCollection::class, $objects);
        $this->assertEquals(0, count($objects));
    }

    public function test_find_action_definition(): void
    {
        $manager = $this->createManagerMockOne(ActionDefinition::class);
        $object = (new DoctrineStorage($manager))->findActionDefinition($primaryKey = rand(1, 100));
        $this->assertInstanceOf(ActionDefinition::class, $object);
    }

    public function test_find_action_definition_empty(): void
    {
        $manager = $this->createManagerMockOne(ActionDefinition::class, true);
        $object = (new DoctrineStorage($manager))->findActionDefinition($primaryKey = rand(1, 100));
        $this->assertNull($object);
    }

    public function test_find_action_definition_by(): void
    {
        $manager = $this->createManagerMockMany(ActionDefinition::class);
        /** @var object&StorageTrait $storage */
        $storage = $this->wrapStorage(new DoctrineStorage($manager));
        $objects = $storage->findActionDefinitionBy([]);
        $this->assertInstanceOf(ActionDefinitionCollection::class, $objects);
        $this->assertGreaterThan(0, count($objects));
    }

    public function test_find_action_definition_by_empty(): void
    {
        $manager = $this->createManagerMockMany(ActionDefinition::class, true);
        /** @var object&StorageTrait $storage */
        $storage = $this->wrapStorage(new DoctrineStorage($manager));
        $objects = $storage->findActionDefinitionBy([]);
        $this->assertInstanceOf(ActionDefinitionCollection::class, $objects);
        $this->assertEquals(0, count($objects));
    }

    public function test_find_level(): void
    {
        $manager = $this->createManagerMockOne(Level::class);
        /** @var object&StorageTrait $storage */
        $storage = $this->wrapStorage(new DoctrineStorage($manager));
        $object = $storage->findLevel($primaryKey = rand(1, 100));
        $this->assertInstanceOf(Level::class, $object);
    }

    public function test_find_level_empty(): void
    {
        $manager = $this->createManagerMockOne(Level::class, true);
        /** @var object&StorageTrait $storage */
        $storage = $this->wrapStorage(new DoctrineStorage($manager));
        $object = $storage->findLevel($primaryKey = rand(1, 100));
        $this->assertNull($object);
    }

    public function test_find_level_by(): void
    {
        $manager = $this->createManagerMockMany(Level::class);
        /** @var object&StorageTrait $storage */
        $storage = $this->wrapStorage(new DoctrineStorage($manager));
        $objects = $storage->findLevelBy([]);
        $this->assertInstanceOf(LevelCollection::class, $objects);
        $this->assertGreaterThan(0, count($objects));
    }

    public function test_find_level_by_empty(): void
    {
        $manager = $this->createManagerMockMany(Level::class, true);
        /** @var object&StorageTrait $storage */
        $storage = $this->wrapStorage(new DoctrineStorage($manager));
        $objects = $storage->findLevelBy([]);
        $this->assertInstanceOf(LevelCollection::class, $objects);
        $this->assertEquals(0, count($objects));
    }

    public function test_save_player(): void
    {
        $manager = $this->createManagerMockSave();
        $object = $this->createMock(Player::class);
        /** @var object&StorageTrait $storage */
        $storage = $this->wrapStorage(new DoctrineStorage($manager));
        $storage->savePlayer($object);
    }

    public function test_save_achievement_definition(): void
    {
        $manager = $this->createManagerMockSave();
        $object = $this->createMock(AchievementDefinition::class);
        /** @var object&StorageTrait $storage */
        $storage = $this->wrapStorage(new DoctrineStorage($manager));
        $storage->saveAchievementDefinition($object);
    }

    public function test_save_action_definition(): void
    {
        $manager = $this->createManagerMockSave();
        $object = $this->createMock(ActionDefinition::class);
        /** @var object&StorageTrait $storage */
        $storage = $this->wrapStorage(new DoctrineStorage($manager));
        $storage->saveActionDefinition($object);
    }

    public function test_save_personal_action(): void
    {
        $manager = $this->createManagerMockSave();
        $object = $this->createMock(PersonalAction::class);
        /** @var object&StorageTrait $storage */
        $storage = $this->wrapStorage(new DoctrineStorage($manager));
        $storage->savePersonalAction($object);
    }

    public function test_save_personal_achievement(): void
    {
        $manager = $this->createManagerMockSave();
        $object = $this->createMock(PersonalAchievement::class);
        /** @var object&StorageTrait $storage */
        $storage = $this->wrapStorage(new DoctrineStorage($manager));
        $storage->savePersonalAchievement($object);
    }

    public function test_save_level(): void
    {
        $manager = $this->createManagerMockSave();
        $object = $this->createMock(Level::class);
        /** @var object&StorageTrait $storage */
        $storage = $this->wrapStorage(new DoctrineStorage($manager));
        $storage->saveLevel($object);
    }

    public function test_refresh_player(): void
    {
        $manager = $this->createManagerMockRefresh();
        $object = $this->createMock(Player::class);
        /** @var object&StorageTrait $storage */
        $storage = $this->wrapStorage(new DoctrineStorage($manager));
        $storage->refreshPlayer($object);
    }

    public function test_save_activity(): void
    {
        $manager = $this->createManagerMockSave();
        $object = $this->createMock(Activity::class);
        /** @var object&StorageTrait $storage */
        $storage = $this->wrapStorage(new DoctrineStorage($manager));
        $storage->saveActivity($object);
    }

    public function test_find_activity(): void
    {
        $manager = $this->createManagerMockOne(Activity::class);
        /** @var object&StorageTrait $storage */
        $storage = $this->wrapStorage(new DoctrineStorage($manager));
        $object = $storage->findActivity($primaryKey = rand(1, 100));
        $this->assertInstanceOf(Activity::class, $object);
    }

    public function test_find_activity_empty(): void
    {
        $manager = $this->createManagerMockOne(Activity::class, true);
        /** @var object&StorageTrait $storage */
        $storage = $this->wrapStorage(new DoctrineStorage($manager));
        $object = $storage->findActivity($primaryKey = rand(1, 100));
        $this->assertNull($object);
    }

    public function test_find_activity_by(): void
    {
        $manager = $this->createManagerMockMany(Activity::class);
        /** @var object&StorageTrait $storage */
        $storage = $this->wrapStorage(new DoctrineStorage($manager));
        $objects = $storage->findActivityBy([]);
        $this->assertInstanceOf(ActivityCollection::class, $objects);
        $this->assertGreaterThan(0, count($objects));
    }

    public function test_find_activity_by_empty(): void
    {
        $manager = $this->createManagerMockMany(Activity::class, true);
        /** @var object&StorageTrait $storage */
        $storage = $this->wrapStorage(new DoctrineStorage($manager));
        $objects = $storage->findActivityBy([]);
        $this->assertInstanceOf(ActivityCollection::class, $objects);
        $this->assertEquals(0, count($objects));
    }
}
