<?php

declare(strict_types=1);

namespace App\Tests\DataFixtures\Entity;

use App\Entity\User;

class UserData
{
    public static function get(): User
    {
        $entity = new User();

        $entity
            ->setEmail('test@test.test')
            ->setUsername('test')
            ->setPlainPassword('testpw')
            ->setEnabled(true);

        return $entity;
    }
}
