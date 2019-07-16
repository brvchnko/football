<?php

declare(strict_types=1);

namespace App\Model\Token;

class User
{
    /**
     * @var string|null
     */
    private $id;

    /**
     * @var string|null
     */
    private $email;

    /**
     * @var array|null
     */
    private $roles = [];


    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(?string $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getRoles(): ?array
    {
        return $this->roles;
    }

    public function setRoles(?array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }
}
