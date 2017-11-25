<?php

namespace Component\Entity;

interface ActivityInterface
{
    public function getName(): string;

    public function setName(string $name): void;

    public function getData(): array;

    public function setData(array $data): void;

    public function getCreatedAt(): \DateTime;
}
