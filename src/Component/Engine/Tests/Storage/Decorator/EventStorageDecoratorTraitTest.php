<?php

namespace Component\Engine\Tests\Storage\Decorator;

use PHPUnit\Framework\TestCase;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Component\Engine\Storage\StorageInterface;
use Component\Engine\Storage\Decorator\EventStorageDecoratorTrait;
use Component\Entity\PlayerInterface;
use Component\Entity\Achievement\PersonalAchievementInterface;
use Component\Entity\Achievement\PersonalActionInterface;
use Component\Engine\Events;
use Component\Engine\Event\ObjectEvent;

class EventStorageDecoratorTraitTest extends TestCase
{
    public function createInstaceWithStorage(
        StorageInterface $storage,
        EventDispatcherInterface $eventDispatcher
    ): object {
        return new class($storage, $eventDispatcher) {
            use EventStorageDecoratorTrait;

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

        /** @var object&EventStorageDecoratorTrait $instance */
        $instance = $this->createInstaceWithStorage($storage, $eventDispatcher);
        $this->assertSame($eventDispatcher, $instance->getEventDispatcher());
    }

    public function test_save_payer(): void
    {
        $storage = $this->createMock(StorageInterface::class);
        $eventDispatcher = $this->createMock(EventDispatcherInterface::class);
        $eventDispatcher->expects($this->exactly(2))
            ->method('dispatch')
            ->withConsecutive(
                [$this->stringContains(EVENTS::PRE_SAVE), $this->isInstanceOf(ObjectEvent::class)],
                [$this->stringContains(EVENTS::POST_SAVE), $this->isInstanceOf(ObjectEvent::class)]
            );

        $object = $this->createMock(PlayerInterface::class);
        /** @var object&EventStorageDecoratorTrait $instance */
        $instance = $this->createInstaceWithStorage($storage, $eventDispatcher);
        $instance->savePlayer($object);
    }

    public function test_save_personal_achievement(): void
    {
        $storage = $this->createMock(StorageInterface::class);
        $eventDispatcher = $this->createMock(EventDispatcherInterface::class);
        $eventDispatcher->expects($this->exactly(2))
            ->method('dispatch')
            ->withConsecutive(
                [$this->stringContains(EVENTS::PRE_SAVE), $this->isInstanceOf(ObjectEvent::class)],
                [$this->stringContains(EVENTS::POST_SAVE), $this->isInstanceOf(ObjectEvent::class)]
            );

        $object = $this->createMock(PersonalAchievementInterface::class);
        /** @var object&EventStorageDecoratorTrait $instance */
        $instance = $this->createInstaceWithStorage($storage, $eventDispatcher);
        $instance->savePersonalAchievement($object);
    }

    public function test_save_personal_action(): void
    {
        $storage = $this->createMock(StorageInterface::class);
        $eventDispatcher = $this->createMock(EventDispatcherInterface::class);
        $eventDispatcher->expects($this->exactly(2))
            ->method('dispatch')
            ->withConsecutive(
                [$this->stringContains(EVENTS::PRE_SAVE), $this->isInstanceOf(ObjectEvent::class)],
                [$this->stringContains(EVENTS::POST_SAVE), $this->isInstanceOf(ObjectEvent::class)]
            );

        $object = $this->createMock(PersonalActionInterface::class);
        /** @var object&EventStorageDecoratorTrait $instance */
        $instance = $this->createInstaceWithStorage($storage, $eventDispatcher);
        $instance->savePersonalAction($object);
    }
}
