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
        $object = $this->wrapStorage(new DoctrineStorage($manager))->findPlayer($primaryKey = rand(1, 100));
        $this->assertInstanceOf(Player::class, $object);
    }

    public function test_find_player_empty(): void
    {
        $manager = $this->createManagerMockOne(Player::class, true);
        $object = $this->wrapStorage(new DoctrineStorage($manager))->findPlayer($primaryKey = rand(1, 100));
        $this->assertNull($object);
    }

    public function test_find_player_by(): void
    {
        $manager = $this->createManagerMockMany(Player::class);
        $objects = $this->wrapStorage(new DoctrineStorage($manager))->findPlayerBy([]);
        $this->assertInstanceOf(PlayerCollection::class, $objects);
        $this->assertGreaterThan(0, count($objects));
    }

    public function test_find_player_by_empty(): void
    {
        $manager = $this->createManagerMockMany(Player::class, true);
        $objects = $this->wrapStorage(new DoctrineStorage($manager))->findPlayerBy([]);
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
        $objects = $this->wrapStorage(new DoctrineStorage($manager))->findAchievementDefinitionBy([]);
        $this->assertInstanceOf(AchievementDefinitionCollection::class, $objects);
        $this->assertGreaterThan(0, count($objects));
    }

    public function test_find_achievement_definition_by_empty(): void
    {
        $manager = $this->createManagerMockMany(AchievementDefinition::class, true);
        $objects = $this->wrapStorage(new DoctrineStorage($manager))->findAchievementDefinitionBy([]);
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
        $objects = $this->wrapStorage(new DoctrineStorage($manager))->findActionDefinitionBy([]);
        $this->assertInstanceOf(ActionDefinitionCollection::class, $objects);
        $this->assertGreaterThan(0, count($objects));
    }

    public function test_find_action_definition_by_empty(): void
    {
        $manager = $this->createManagerMockMany(ActionDefinition::class, true);
        $objects = $this->wrapStorage(new DoctrineStorage($manager))->findActionDefinitionBy([]);
        $this->assertInstanceOf(ActionDefinitionCollection::class, $objects);
        $this->assertEquals(0, count($objects));
    }

    public function test_find_level(): void
    {
        $manager = $this->createManagerMockOne(Level::class);
        $object = $this->wrapStorage(new DoctrineStorage($manager))->findLevel($primaryKey = rand(1, 100));
        $this->assertInstanceOf(Level::class, $object);
    }

    public function test_find_level_empty(): void
    {
        $manager = $this->createManagerMockOne(Level::class, true);
        $object = $this->wrapStorage(new DoctrineStorage($manager))->findLevel($primaryKey = rand(1, 100));
        $this->assertNull($object);
    }

    public function test_find_level_by(): void
    {
        $manager = $this->createManagerMockMany(Level::class);
        $objects = $this->wrapStorage(new DoctrineStorage($manager))->findLevelBy([]);
        $this->assertInstanceOf(LevelCollection::class, $objects);
        $this->assertGreaterThan(0, count($objects));
    }

    public function test_find_level_by_empty(): void
    {
        $manager = $this->createManagerMockMany(Level::class, true);
        $objects = $this->wrapStorage(new DoctrineStorage($manager))->findLevelBy([]);
        $this->assertInstanceOf(LevelCollection::class, $objects);
        $this->assertEquals(0, count($objects));
    }

    public function test_save_player(): void
    {
        $manager = $this->createManagerMockSave();
        $object = $this->createMock(Player::class);
        $this->wrapStorage(new DoctrineStorage($manager))->savePlayer($object);
    }

    public function test_save_achievement_definition(): void
    {
        $manager = $this->createManagerMockSave();
        $object = $this->createMock(AchievementDefinition::class);
        $this->wrapStorage(new DoctrineStorage($manager))->saveAchievementDefinition($object);
    }

    public function test_save_action_definition(): void
    {
        $manager = $this->createManagerMockSave();
        $object = $this->createMock(ActionDefinition::class);
        $this->wrapStorage(new DoctrineStorage($manager))->saveActionDefinition($object);
    }

    public function test_save_personal_action(): void
    {
        $manager = $this->createManagerMockSave();
        $object = $this->createMock(PersonalAction::class);
        $this->wrapStorage(new DoctrineStorage($manager))->savePersonalAction($object);
    }

    public function test_save_personal_achievement(): void
    {
        $manager = $this->createManagerMockSave();
        $object = $this->createMock(PersonalAchievement::class);
        $this->wrapStorage(new DoctrineStorage($manager))->savePersonalAchievement($object);
    }

    public function test_save_level(): void
    {
        $manager = $this->createManagerMockSave();
        $object = $this->createMock(Level::class);
        $this->wrapStorage(new DoctrineStorage($manager))->saveLevel($object);
    }

    public function test_refresh_player(): void
    {
        $manager = $this->createManagerMockRefresh();
        $object = $this->createMock(Player::class);
        $this->wrapStorage(new DoctrineStorage($manager))->refreshPlayer($object);
    }
}
