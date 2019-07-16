<?php

declare(strict_types=1);

namespace App\Model\Response;

class LeagueOutput
{
    /**
     * @var int|null
     */
    private $id;
    /**
     * @var string|null
     */
    private $name;

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
}