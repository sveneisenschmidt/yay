<?php

namespace Component\Engine\Event;

use Symfony\Component\EventDispatcher\Event;

class ObjectEvent extends Event
{
    /** @var object */
    protected $object;

    public function __construct(object $object)
    {
        $this->object = $object;
    }

    public function getObject(): object
    {
        return $this->object;
    }
}