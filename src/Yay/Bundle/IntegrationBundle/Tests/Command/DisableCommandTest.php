<?php

namespace Yay\Bundle\EngineBundle\Tests\Command;

use PHPUnit\Framework\TestCase;
use Yay\Bundle\IntegrationBundle\Command\DisableCommand;

class DisableCommandTest extends TestCase
{
    /**
     * @test
     */
    public function configure()
    {
        $command = new DisableCommand();
        $this->assertEquals('yay:integration:disable', $command->getName());
    }
}
