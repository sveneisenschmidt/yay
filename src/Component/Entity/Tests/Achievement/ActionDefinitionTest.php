<?php

namespace Component\Entity\Tests\Achievement;

use PHPUnit\Framework\TestCase;
use Component\Entity\Achievement\ActionDefinition;
use Component\Entity\Achievement\PersonalActionCollection;

class ActionDefinitionTest extends TestCase
{
    /**
     * @var \Faker\Generator
     */
    protected $faker;

    public function setUp(): void
    {
        $this->faker = \Faker\Factory::create();
    }

    public function test_set_get_scalar(): void
    {
        $instance = new ActionDefinition($name = $this->faker->word);
        $this->assertEquals($name, $instance->getName());
        $this->assertEquals($name, (string) $instance);

        $instance->setLabel($label = $this->faker->word);
        $instance->setDescription($description = $this->faker->word);
        $instance->setImageUrl($imageUrl = 'https://example.org/example.png');

        $this->assertEquals($label, $instance->getLabel());
        $this->assertEquals($description, $instance->getDescription());
        $this->assertEquals($imageUrl, $instance->getImageUrl());
    }

    public function test_get_personal_actions(): void
    {
        $instance = new ActionDefinition($name = $this->faker->word);
        $this->assertInstanceOf(PersonalActionCollection::class, $instance->getPersonalActions());
    }
}
