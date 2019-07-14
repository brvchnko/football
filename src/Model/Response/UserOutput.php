<?php

declare(strict_types=1);

namespace App\Model\Response;

class UserOutput
{
    /**
     * @var string|null
     */
    private $username;
    /**
     * @var string|null
     */
    private $email;


    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(?string $username): self
    {
        $this->username = $username;

        return $this;
    }
}