<?php

namespace Component\Entity;

class Activity implements ActivityInterface
{
    const PERSONAL_ACTION_GRANTED = 'personal_action_granted';

    const PERSONAL_ACHIEVEMENT_GRANTED = 'personal_achievement_granted';

    const PLAYER_CREATED = 'player_created';

    const SCORE_CHANGED = 'score_changed';

    const LEVEL_CHANGED = 'level_changed';

    /** @var int */
    protected $id;

    /** @var string */
    protected $name;

    /** @var PlayerInterface */
    protected $player;

    /** @var array */
    protected $data;

    /** @var \DateTime */
    protected $createdAt;

    public function __construct(
        string $name,
        PlayerInterface $player,
        array $data = [],
        \DateTime $createdAt = null
    ) {
        $this->setPlayer($player);
        $this->setName($name);
        $this->setData($data);
        $this->setCreatedAt($createdAt ?: new \DateTime());
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setPlayer(PlayerInterface $player): void
    {
        $this->player = $player;
    }

    public function getPlayer(): PlayerInterface
    {
        return $this->player;
    }

    public function setData(array $data): void
    {
        $this->data = $data;
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function setCreatedAt(\DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }
}
