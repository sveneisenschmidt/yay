<?php

namespace Component\Entity\Achievement;

class ActionDefinition implements ActionDefinitionInterface
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $label = '';

    /**
     * @var string
     */
    protected $description = '';

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
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * {@inheritdoc}
     */
    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * @param string $label
     */
    public function setLabel(string $label)
    {
        $this->label = $label;
    }

    /**
     * {@inheritdoc}
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description)
    {
        $this->description = $description;
    }

    /**
     * @return PersonalActionCollection
     */
    public function getPersonalActions(): PersonalActionCollection
    {
        return $this->personalActions;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->name;
    }
}
