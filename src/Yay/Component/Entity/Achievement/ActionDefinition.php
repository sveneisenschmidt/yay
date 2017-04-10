<?php

namespace Yay\Component\Entity\Achievement;

use Yay\Component\Entity\Achievement\ActionDefinitionInterface;
use Yay\Component\Entity\Achievement\PersonalActionCollection;
use Yay\Component\Entity\Achievement\PersonalActionInterface;

class ActionDefinition implements ActionDefinitionInterface
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var array|PersonalActionInterface[]
     */
    protected $personalActions;

    /**
     * ActionDefinition constructor.
     *
     * @param string $name
     */
    public function __construct(string $name)
    {
        $this->personalActions = new PersonalActionCollection();
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
