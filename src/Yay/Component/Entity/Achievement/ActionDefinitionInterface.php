<?php

namespace Yay\Component\Entity\Achievement;

interface ActionDefinitionInterface
{
    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @return string
     */
    public function getLabel(): string;

    /**
     * @return string
     */
    public function getDescription(): string;
}
