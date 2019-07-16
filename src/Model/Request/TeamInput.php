<?php

declare(strict_types=1);

namespace App\Model\Request;

class TeamInput implements InputModelInterface
{
    /**
     * @var string|null
     */
    private $name;

    /**
     * @var string|null
     */
    private $strip;

    /**
     * @var array
     */
    private $leagues = [];


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

    public function getLeagues(): array
    {
        return $this->leagues;
    }

    public function setLeagues(array $leagues): self
    {
        $this->leagues = $leagues;

        return $this;
    }
}