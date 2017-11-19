<?php

namespace Component\Entity\Achievement;

interface LevelInterface
{
    /**
     * @return int
     */
    public function getPoints(): int;

    /**
     * @return int
     */
    public function getLevel(): int;

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
