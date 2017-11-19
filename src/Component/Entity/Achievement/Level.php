<?php

namespace Component\Entity\Achievement;

class Level implements LevelInterface
{
    /** @var string */
    protected $name;

    /** @var string */
    protected $label = '';

    /** @var string */
    protected $description = '';

    /** @var int */
    protected $level;

    /** @var int */
    protected $points;

    public function __construct(string $name, int $level, int $points)
    {
        $this->name = $name;
        $this->level = $level;
        $this->points = $points;
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

    public function getLevel(): int
    {
        return (int) $this->level;
    }

    public function setLevel(int $level): void
    {
        $this->level = $level;
    }

    public function getPoints(): int
    {
        return (int) $this->points;
    }

    public function setPoints(int $points): void
    {
        $this->points = $points;
    }
}
