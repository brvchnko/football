<?php

declare(strict_types=1);

namespace App\Factory;

use App\Entity\User;

class UserFactory
{
    private const USER_ROLES = ['ROLE_USER', 'ROLE_ADMIN'];

    public function updateUserRoles(User $user, array $roles = []): User
    {
        if (empty($roles)) {
            $roles = self::USER_ROLES;
        }

        return $user->setRoles($roles);
    }
}
