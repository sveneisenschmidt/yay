<?php
namespace Component\Entity;

use Component\Entity\ActivityInterface;

class Activity implements ActivityInterface
{
    const PERSONAL_ACTION_GRANTED = 'personal_action_granted';
    const PERSONAL_ACHIEVEMENT_GRANTED = 'personal_achievement_granted';
    const PLAYER_CREATED = 'player_created';

    /** @var int */
    protected $id;

    /** @var string */
    protected $name;

    /** @var array */
    protected $data;
    
    /** @var \DateTime */
    protected $createdAt;

    public function __construct(string $name, array $data = [], \DateTime $createdAt = null)
    {
        $this->name = $name;
        $this->data = $data;
        $this->createdAt = $createdAt ? $createdAt : new \DateTime();
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }
    
    public function getName(): string
    {
        return $this->name;
    }

    public function setData(array $data): void
    {
        $this->data = $data;
    }

    public function getData(): array
    {
        return $this->data;
    }
    
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }
}
