<?php

namespace Component\Entity\Achievement;

interface ActionDefinitionInterface
{
    public function getName(): string;

    public function getLabel(): string;

    public function getDescription(): string;
}
