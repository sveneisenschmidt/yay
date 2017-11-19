<?php

namespace Component\Entity\Tests\Achievement;

use Faker\Factory as FakerFactory;
use PHPUnit\Framework\TestCase;
use Component\Entity\Achievement\ActionDefinition;
use Component\Entity\Achievement\PersonalActionCollection;

class ActionDefinitionTest extends TestCase
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
        $instance = new ActionDefinition($name = $this->faker->word);
        $this->assertEquals($name, $instance->getName());
        $this->assertEquals($name, (string) $instance);

        $instance->setLabel($label = $this->faker->word);
        $instance->setDescription($description = $this->faker->word);
        $this->assertEquals($label, $instance->getLabel());
        $this->assertEquals($description, $instance->getDescription());
    }

    /**
     * @test
     */
    public function get_personal_actions()
    {
        $instance = new ActionDefinition($name = $this->faker->word);
        $this->assertInstanceOf(PersonalActionCollection::class, $instance->getPersonalActions());
    }
}
