<?php

namespace Component\Entity\Tests;

use Faker\Factory as FakerFactory;
use PHPUnit\Framework\TestCase;
use Component\Entity\Activity;

class ActivityTest extends TestCase
{
    public function setUp(): void
    {
        $this->faker = FakerFactory::create();
    }

    public function test_set_get_scalar(): void
    {
        $instance = new Activity(
            $name = $this->faker->name, 
            $data = [1, 2, 3],
            $createdAt = $this->faker->dateTime()
        );

        $this->assertEquals($name, $instance->getName());
        $this->assertEquals($data, $instance->getData());
        $this->assertEquals($createdAt, $instance->getCreatedAt());

        $instance->setName($name = $this->faker->name);
        $instance->setData($data = [4, 5, 6]);

        $this->assertEquals($name, $instance->getName());
        $this->assertEquals($data, $instance->getData());
    }
}
