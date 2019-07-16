<?php

declare(strict_types=1);

namespace App\Model\Token;

class Data
{
    /**
     * @var Meta|null
     */
    private $meta;

    /**
     * @var User|null
     */
    private $user;

    public function getMeta(): ?Meta
    {
        return $this->meta;
    }

    public function setMeta(?Meta $meta): self
    {
        $this->meta = $meta;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
