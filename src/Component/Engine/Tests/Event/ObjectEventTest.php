<?php

namespace Component\Engine\Tests\Event;

use PHPUnit\Framework\TestCase;
use Component\Engine\Event\ObjectEvent;

class ObjectEventTest extends TestCase
{
    public function test_set_get_object()
    {
        $object = new \stdClass();
        $event = new ObjectEvent($object);

        $this->assertSame($object, $event->getObject());
    }
}
