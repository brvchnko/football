<?php

declare(strict_types=1);

namespace App\Model\Request;

class LeagueInput implements InputModelInterface
{
    /**
     * @var string|null
     */
    private $name;


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