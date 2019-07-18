<?php

declare(strict_types=1);

namespace App\Service;

use App\Factory\UserFactory;
use App\Model\Request\UserInput;
use App\Model\Response\UserOutput;
use FOS\UserBundle\Model\UserManagerInterface;

class UserService
{
    /** @var UserFactory */
    private $factory;

    public function __construct(UserFactory $factory)
    {
        $this->factory = $factory;
    }

    public function create(UserInput $input, UserManagerInterface $userManager): UserOutput
    {
        $entity = $this->factory->createEntityFromModel($input);

        $userManager->updateUser($entity);

        return $this->factory->createModelFromEntity($entity);
    }
}
