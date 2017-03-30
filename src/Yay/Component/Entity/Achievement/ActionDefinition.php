<?php

namespace Yay\Component\Entity\Achievement;

use Yay\Component\Entity\Achievement\ActionDefinitionInterface;
use Yay\Component\Entity\Achievement\StepCollection;
use Yay\Component\Entity\Achievement\StepInterface;

class ActionDefinition implements ActionDefinitionInterface
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var array|StepInterface[]
     */
    protected $steps;

    /**
     * ActionDefinition constructor.
     *
     * @param string $name
     */
    public function __construct(string $name)
    {
        $this->steps = new StepCollection();
        $this->name = $name;
    }

    /**
     * {@inheritDoc}
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->name;
    }
}
