<?php

declare(strict_types=1);

namespace App\Model\Request;

class UserInput implements InputModelInterface
{
    /**
     * @var string|null
     */
    private $username;
    /**
     * @var string|null
     */
    private $email;
    /**
     * @var string|null
     */
    private $password;


    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): self
    {
        $this->password = $password;

        return $this;
    }

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