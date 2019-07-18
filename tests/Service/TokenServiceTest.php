<?php

declare(strict_types=1);

namespace App\Tests\Service;

use App\Exceptions\TokenException;
use App\Repository\UserRepository;
use App\Service\TokenService;
use App\Tests\DataFixtures\Entity\UserData;
use App\Tests\DataFixtures\Model\Request\TokenInputData;
use App\Util\TokenUtil;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class TokenServiceTest extends TestCase
{
    /** @var UserRepository|MockObject */
    private $repository;
    /** @var UserPasswordEncoderInterface|MockObject */
    private $encoder;
    /** @var TokenUtil|MockObject */
    private $util;
    /** @var TokenService */
    private $sub;

    protected function setUp()
    {
        $this->repository = $this->createMock(UserRepository::class);
        $this->encoder = $this->createMock(UserPasswordEncoderInterface::class);
        $this->util = $this->createMock(TokenUtil::class);

        $this->sub = new TokenService($this->repository, $this->encoder, $this->util);
    }

    public function testWillThrowExceptionIfUserNotFound(): void
    {
        $input = TokenInputData::get();

        $this->repository
            ->expects($this->once())
            ->method('findOneByEmailAndStatus')
            ->with($input->getEmail())
            ->willReturn(null);

        $this->expectException(TokenException::class);

        $this->sub->create($input);
    }

    public function testWillThrowExceptionIfPasswordIsInvalid(): void
    {
        $input = TokenInputData::get();
        $user = UserData::get();

        $this->repository
            ->expects($this->once())
            ->method('findOneByEmailAndStatus')
            ->with($input->getEmail())
            ->willReturn($user);

        $this->encoder
            ->expects($this->once())
            ->method('isPasswordValid')
            ->with($user, $input->getPassword())
            ->willReturn(false);

        $this->expectException(TokenException::class);

        $this->sub->create($input);
    }

    public function testWillCreateToken(): void
    {
        $input = TokenInputData::get();
        $user = UserData::get();

        $this->repository
            ->expects($this->once())
            ->method('findOneByEmailAndStatus')
            ->with($input->getEmail())
            ->willReturn($user);

        $this->encoder
            ->expects($this->once())
            ->method('isPasswordValid')
            ->with($user, $input->getPassword())
            ->willReturn(true);

        $this->util
            ->expects($this->once())
            ->method('create')
            ->with($user)
            ->willReturn('token');

        $this->sub->create($input);
    }
}
