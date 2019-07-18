<?php

declare(strict_types=1);

namespace App\Factory;

use App\Entity\User;
use App\Model\Request\UserInput;
use App\Model\Response\UserOutput;

class UserFactory
{
    private const USER_ROLES = ['ROLE_USER', 'ROLE_ADMIN'];

    public function createEntityFromModel(UserInput $source, User $entity = null): User
    {
        if (null === $entity) {
            $entity = new User();
        }

        $entity
            ->setEmail($source->getEmail())
            ->setUsername($source->getUsername())
            ->setPlainPassword($source->getPassword())
            ->setEnabled(true)
            ->setRoles(self::USER_ROLES);

        return $entity;
    }

    public function createModelFromEntity(User $entity): UserOutput
    {
        $outputModel = new UserOutput();

        $outputModel
            ->setUsername($entity->getUsername())
            ->setEmail($entity->getEmail());

        return $outputModel;
    }
}
