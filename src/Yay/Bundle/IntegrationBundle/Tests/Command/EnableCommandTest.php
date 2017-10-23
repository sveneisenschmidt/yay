<?php

namespace Yay\Bundle\EngineBundle\Tests\Command;

use PHPUnit\Framework\TestCase;
use Yay\Bundle\IntegrationBundle\Command\EnableCommand;

class EnableCommandTest extends TestCase
{
    /**
     * @test
     */
    public function configure()
    {
        $command = new EnableCommand();
        $this->assertEquals('yay:integration:enable', $command->getName());
    }
}
