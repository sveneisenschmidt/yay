<?php

namespace Component\Entity\Achievement;

class ActionDefinition implements ActionDefinitionInterface
{
    /** @var string */
    protected $name;

    /** @var string */
    protected $label = '';

    /** @var string */
    protected $description = '';

    /**
     * @var array<PersonalActionInterface>
     */
    protected $personalActions;

    /** @var string */
    protected $imageUrl;

    public function __construct(string $name)
    {
        $this->personalActions = new PersonalActionCollection();
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function setLabel(string $label): void
    {
        $this->label = $label;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getPersonalActions(): PersonalActionCollection
    {
        return $this->personalActions;
    }

    public function __toString(): string
    {
        return $this->name;
    }

    public function getImageUrl(): string
    {
        return $this->imageUrl;
    }

    public function setImageUrl(string $imageUrl): void
    {
        $this->imageUrl = $imageUrl;
    }
}
