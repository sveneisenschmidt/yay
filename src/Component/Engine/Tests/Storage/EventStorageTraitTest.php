<?php

namespace Component\Engine\Tests\Storage;

use PHPUnit\Framework\TestCase;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Component\Engine\Storage\StorageInterface;
use Component\Engine\Storage\EventStorageTrait;
use Component\Entity\PlayerInterface;
use Component\Entity\Achievement\PersonalAchievementInterface;
use Component\Entity\Achievement\PersonalActionInterface;

class EventStorageTraitTest extends TestCase
{
    public function createInstaceWithStorage(
        StorageInterface $storage, 
        EventDispatcherInterface $eventDispatcher
    ): object {
        return new class($storage, $eventDispatcher) {
            use EventStorageTrait;

            public function __construct(
                StorageInterface $storage, 
                EventDispatcherInterface $eventDispatcher
            ) {
                $this->setStorage($storage);
                $this->setEventDispatcher($eventDispatcher);
            }
        };
    }
    
    public function test_set_get_event_dispatcher(): void
    {
        $storage = $this->createMock(StorageInterface::class);
        $eventDispatcher = $this->createMock(EventDispatcherInterface::class);

        $instance = $this->createInstaceWithStorage($storage, $eventDispatcher);
        $this->assertSame($eventDispatcher, $instance->getEventDispatcher());
    }

    public function test_save_payer(): void
    {
        $storage = $this->createMock(StorageInterface::class);
        $eventDispatcher = $this->createMock(EventDispatcherInterface::class);
        $eventDispatcher->expects($this->exactly(2))
            ->method('dispatch');

        $object = $this->createMock(PlayerInterface::class);
        $instance = $this->createInstaceWithStorage($storage, $eventDispatcher);
        $instance->savePlayer($object);
    }

    public function test_save_personal_achievement(): void
    {
        $storage = $this->createMock(StorageInterface::class);
        $eventDispatcher = $this->createMock(EventDispatcherInterface::class);
        $eventDispatcher->expects($this->exactly(2))
            ->method('dispatch');

        $object = $this->createMock(PersonalAchievementInterface::class);
        $instance = $this->createInstaceWithStorage($storage, $eventDispatcher);
        $instance->savePersonalAchievement($object);
    }    

    public function test_save_personal_action(): void
    {
        $storage = $this->createMock(StorageInterface::class);
        $eventDispatcher = $this->createMock(EventDispatcherInterface::class);
        $eventDispatcher->expects($this->exactly(2))
            ->method('dispatch');

        $object = $this->createMock(PersonalActionInterface::class);
        $instance = $this->createInstaceWithStorage($storage, $eventDispatcher);
        $instance->savePersonalAction($object);
    }    
}
