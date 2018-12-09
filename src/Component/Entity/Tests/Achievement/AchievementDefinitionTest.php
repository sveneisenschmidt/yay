<?php

namespace Component\Entity\Tests\Achievement;

use PHPUnit\Framework\TestCase;
use Component\Entity\Achievement\AchievementDefinition;
use Component\Entity\Achievement\ActionDefinition;
use Component\Entity\Achievement\ActionDefinitionCollection;

class AchievementDefinitionTest extends TestCase
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
        $instance = new AchievementDefinition($name = $this->faker->word);
        $this->assertEquals($name, $instance->getName());

        $instance->setPoints($points = rand(100, 1000));
        $instance->setLabel($label = $this->faker->word);
        $instance->setDescription($description = $this->faker->word);
        $instance->setImageUrl($imageUrl = 'https://example.org/example.png');

        $this->assertEquals($points, $instance->getPoints());
        $this->assertEquals($label, $instance->getLabel());
        $this->assertEquals($description, $instance->getDescription());
        $this->assertEquals($imageUrl, $instance->getImageUrl());
    }

    public function test_get_action_definitions(): void
    {
        $instance = new AchievementDefinition($name = $this->faker->word);
        $this->assertInstanceOf(ActionDefinitionCollection::class, $instance->getActionDefinitions());
        $this->assertInstanceOf(\DateTime::class, $instance->getCreatedAt());
    }

    public function test_has_add_action_definition(): void
    {
        $instance = new AchievementDefinition($name1 = $this->faker->word);
        $actionDefinition = new ActionDefinition($name2 = $this->faker->word);

        $this->assertCount(0, $instance->getActionDefinitions());
        $instance->addActionDefinition($actionDefinition);
        $this->assertCount(1, $instance->getActionDefinitions());
        $instance->addActionDefinition($actionDefinition);
        $this->assertCount(1, $instance->getActionDefinitions());
    }
}
