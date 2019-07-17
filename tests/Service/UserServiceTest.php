<?php

namespace App\Tests\Service;

use App\DataTransformer\UserDataTransformer;
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
    /** @var UserDataTransformer|MockObject */
    private $transformer;
    /** @var UserFactory|MockObject */
    private $factory;
    /** @var UserService */
    private $sub;

    protected function setUp()
    {
        $this->transformer = $this->createMock(UserDataTransformer::class);
        $this->factory = $this->createMock(UserFactory::class);

        $this->sub = new UserService($this->transformer, $this->factory);
    }

    /**
     * @test
     */
    public function willCreateUser(): void
    {
        $input = UserInputData::get();
        $user = UserData::get();

        $this->transformer
            ->expects($this->once())
            ->method('transformToEntity')
            ->with($input)
            ->willReturn($user);

        $this->factory
            ->expects($this->once())
            ->method('updateUserRoles')
            ->with($user)
            ->willReturn($user);

        $manager = $this->createMock(UserManagerInterface::class);

        $manager
            ->expects($this->once())
            ->method('updateUser')
            ->with($user);

        $this->transformer
            ->expects($this->once())
            ->method('transformToModel')
            ->with($user)
            ->willReturn(UserOutputData::get());

        $this->sub->create($input, $manager);
    }
}
