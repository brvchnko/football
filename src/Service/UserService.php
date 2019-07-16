<?php

declare(strict_types=1);

namespace App\Service;

use App\DataTransformer\UserDataTransformer;
use App\Factory\UserFactory;
use App\Model\Request\UserInput;
use App\Model\Response\UserOutput;
use FOS\UserBundle\Model\UserManagerInterface;

class UserService
{
    /** @var UserDataTransformer */
    private $transformer;
    /** @var UserFactory */
    private $factory;

    public function __construct(UserDataTransformer $transformer, UserFactory $factory)
    {
        $this->transformer = $transformer;
        $this->factory = $factory;
    }

    public function create(UserInput $input, UserManagerInterface $userManager): UserOutput
    {
        $entity = $this->factory->updateUserRoles($this->transformer->transformToEntity($input));

        $userManager->updateUser($entity);

        return $this->transformer->transformToModel($entity);
    }
}
