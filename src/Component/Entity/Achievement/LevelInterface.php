<?php

namespace Component\Entity\Achievement;

interface LevelInterface
{
    public function getPoints(): int;

    public function getLevel(): int;

    public function getName(): string;

    public function getLabel(): string;

    public function getDescription(): string;
}
