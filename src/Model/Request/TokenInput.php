<?php

declare(strict_types=1);

namespace App\Model\Request;

class TokenInput implements InputModelInterface
{
    /**
     * @var string|null
     */
    private $email;

    /**
     * @var string|null
     */
    private $password;

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): self
    {
        $this->password = $password;

        return $this;
    }
}
