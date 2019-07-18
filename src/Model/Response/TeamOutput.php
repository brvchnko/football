<?php

declare(strict_types=1);

namespace App\Model\Response;

class TeamOutput
{
    /**
     * @var int|null
     */
    private $id;
    /**
     * @var string|null
     */
    private $name;
    /**
     * @var string|null
     */
    private $strip;
    /**
     * @var LeagueOutput[]|null
     */
    private $leagues;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getStrip(): ?string
    {
        return $this->strip;
    }

    public function setStrip(?string $strip): self
    {
        $this->strip = $strip;

        return $this;
    }

    public function getLeagues(): ?iterable
    {
        return $this->leagues;
    }

    public function setLeagues(iterable $leagues): self
    {
        $this->leagues = $leagues;

        return $this;
    }
}
