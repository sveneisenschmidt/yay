<?php

namespace Yay\Bundle\EngineBundle\Tests\Command;

use PHPUnit\Framework\TestCase;
use Yay\Bundle\IntegrationBundle\Command\ValidateCommand;

class ValidateCommandTest extends TestCase
{
    /**
     * @test
     */
    public function configure()
    {
        $command = new ValidateCommand();
        $this->assertEquals('yay:integration:validate', $command->getName());
    }
}
