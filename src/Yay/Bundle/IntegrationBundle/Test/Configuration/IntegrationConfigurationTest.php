<?php

namespace Yay\Bundle\IntegrationBundle\Test\Configuration;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Yaml\Yaml;
use Yay\Bundle\IntegrationBundle\Configuration\IntegrationConfiguration;

class IntegrationConfigurationTest extends TestCase
{
    /**
     * @test
     */
    public function validate_configuration(): void
    {
        $file = sprintf('%s/Fixture/%s.yml', __DIR__, __FUNCTION__);
        $contents = Yaml::parse(file_get_contents($file));

        $config = (new Processor())->processConfiguration(new IntegrationConfiguration(), $contents);

        $this->assertNotEmpty($config);
        $this->assertArrayHasKey('actions', $config);
        $this->assertArrayHasKey('achievements', $config);
        $this->assertArrayHasKey('validators', $config);

        foreach($config['actions'] as $key => $action) {
            $this->assertEquals('test-action', $key);
            $this->assertArrayHasKey('label', $action);
            $this->assertArrayHasKey('description', $action);
            $this->assertEquals("Test action's label", $action['label']);
            $this->assertEquals("Test action's description", $action['description']);
        }

        $index = 1;
        foreach($config['achievements'] as $key => $achievement) {
            $this->assertEquals(sprintf('test-achievement-0%s', $index), $key);
            $this->assertArrayHasKey('label', $achievement);
            $this->assertArrayHasKey('description', $achievement);
            $this->assertArrayHasKey('points', $achievement);
            $this->assertEquals("Test achievement's label", $achievement['label']);
            $this->assertEquals("Test achievement's description", $achievement['description']);
            $this->assertEquals($index * 50, $achievement['points']);
            $index++;
        }

        $index = 1;
        foreach($config['validators'] as $key => $validator) {
            $this->assertEquals(sprintf('test-achievement-validator-0%s', $index), $key);
            $this->assertArrayHasKey('type', $validator);
            $this->assertArrayHasKey('class', $validator);
            $this->assertArrayHasKey('arguments', $validator);
            $this->assertArrayHasKey('calls', $validator);
            $index++;
        }
    }
}
