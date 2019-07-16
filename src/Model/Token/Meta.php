<?php

declare(strict_types=1);

namespace App\Model\Token;

class Meta
{
    /**
     * @var string|null
     */
    public $id;

    /**
     * @var int|null
     */
    public $tokenExpireTime;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(?string $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getTokenExpireTime(): ?int
    {
        return $this->tokenExpireTime;
    }

    public function setTokenExpireTime(?int $tokenExpireTime): self
    {
        $this->tokenExpireTime = $tokenExpireTime;

        return $this;
    }
}
