<?php

namespace Yay\Component\Entity\Tests\Achievement;

use Faker\Factory as FakerFactory;
use PHPUnit\Framework\TestCase;
use Yay\Component\Entity\Achievement\AchievementDefinition;
use Yay\Component\Entity\Achievement\ActionDefinition;
use Yay\Component\Entity\Achievement\ActionDefinitionCollection;

class AchievementDefinitionTest extends TestCase
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
        $instance = new AchievementDefinition($name = $this->faker->word);
        $this->assertEquals($name, $instance->getName());

        $instance->setPoints($points = rand(100, 1000));
        $instance->setLabel($label = $this->faker->word);
        $instance->setDescription($description = $this->faker->word);

        $this->assertEquals($points, $instance->getPoints());
        $this->assertEquals($label, $instance->getLabel());
        $this->assertEquals($description, $instance->getDescription());
    }

    /**
     * @test
     */
    public function get_action_definitions()
    {
        $instance = new AchievementDefinition($name = $this->faker->word);
        $this->assertInstanceOf(ActionDefinitionCollection::class, $instance->getActionDefinitions());
        $this->assertInstanceOf(\DateTime::class, $instance->getCreatedAt());
    }

    /**
     * @test
     */
    public function has_add_action_definition()
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
