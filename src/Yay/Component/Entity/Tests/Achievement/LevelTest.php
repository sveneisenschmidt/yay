<?php

namespace Yay\Component\Entity\Tests\Achievement;

use Faker\Factory as FakerFactory;
use PHPUnit\Framework\TestCase;
use Yay\Component\Entity\Achievement\Level;

class LevelTest extends TestCase
{
    public function setUp()
    {
        $this->faker = FakerFactory::create();
    }

    /**
     * @test
     */
    public function set_get_scalar()
    {
        $instance = new Level(
            $name = $this->faker->word,
            $level = rand(1, 10),
            $points = rand(100, 1000)
        );

        $this->assertEquals($name, $instance->getName());
        $this->assertEquals($level, $instance->getLevel());
        $this->assertEquals($points, $instance->getPoints());

        $instance->setLevel($level = rand(1, 10));
        $instance->setPoints($points = rand(100, 1000));
        $instance->setLabel($label = $this->faker->word);
        $instance->setDescription($description = $this->faker->word);

        $this->assertEquals($level, $instance->getLevel());
        $this->assertEquals($points, $instance->getPoints());
        $this->assertEquals($label, $instance->getLabel());
        $this->assertEquals($description, $instance->getDescription());
    }
}
