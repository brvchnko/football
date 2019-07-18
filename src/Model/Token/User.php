<?php

declare(strict_types=1);

namespace App\Model\Token;

class User
{
    /**
     * @var string|null
     */
    public $id;

    /**
     * @var string|null
     */
    public $email;

    /**
     * @var array
     */
    public $roles = [];
}
