<?php

declare(strict_types=1);

namespace App\Manager;

use App\DataTransformer\UserDataTransformer;
use App\Entity\User;
use App\Model\Request\UserInput;
use App\Model\Response\UserOutput;
use FOS\UserBundle\Model\UserManagerInterface;

final class UserManager
{
    private const USER_ROLES = ['ROLE_ADMIN'];

    /** @var UserDataTransformer */
    private $dataTransformer;

    public function __construct(UserDataTransformer $transformer)
    {
        $this->dataTransformer = $transformer;
    }

    public function create(UserInput $input, UserManagerInterface $userManager): UserOutput
    {
        $entity = $this->dataTransformer->transform($input);

        $this->updateUserRoles($entity);

        $userManager->updateUser($entity);

        return $this->dataTransformer->reverseTransform($entity);
    }

    private function updateUserRoles(User $user): void
    {
        $user->setRoles(self::USER_ROLES);
    }
}