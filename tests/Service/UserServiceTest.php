<?php

namespace App\Tests\Service;

use App\Factory\UserFactory;
use App\Service\UserService;
use App\Tests\DataFixtures\Entity\UserData;
use App\Tests\DataFixtures\Model\Request\UserInputData;
use App\Tests\DataFixtures\Model\Response\UserOutputData;
use FOS\UserBundle\Model\UserManagerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class UserServiceTest extends TestCase
{
    /** @var UserFactory|MockObject */
    private $factory;
    /** @var UserService */
    private $sub;

    protected function setUp()
    {
        $this->factory = $this->createMock(UserFactory::class);

        $this->sub = new UserService($this->factory);
    }

    public function testWillCreateUser(): void
    {
        $input = UserInputData::get();
        $user = UserData::get();

        $this->factory
            ->expects($this->once())
            ->method('createEntityFromModel')
            ->with($input)
            ->willReturn($user);

        $manager = $this->createMock(UserManagerInterface::class);

        $manager
            ->expects($this->once())
            ->method('updateUser')
            ->with($user);

        $this->factory
            ->expects($this->once())
            ->method('createModelFromEntity')
            ->with($user)
            ->willReturn(UserOutputData::get());

        $this->sub->create($input, $manager);
    }
}
