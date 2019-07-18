<?php

declare(strict_types=1);

namespace App\Tests\Security;

use App\Repository\UserRepository;
use App\Security\TokenProvider;
use App\Tests\DataFixtures\Entity\UserData;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\User;
use Symfony\Component\Security\Core\User\UserInterface;

class TokenProviderTest extends TestCase
{
    /**
     * @var UserRepository&MockObject
     */
    private $userRepositoryMock;

    /**
     * @var TokenProvider
     */
    private $tokenProvider;

    protected function setUp()
    {
        $this->userRepositoryMock = $this->getMockBuilder(UserRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->tokenProvider = new TokenProvider($this->userRepositoryMock);
    }

    public function testWillThrowAuthException(): void
    {
        $email = 'user@email.com';
        $user = null;

        $this->userRepositoryMock
            ->expects($this->once())
            ->method('findOneByEmailAndStatus')
            ->with($email, true)
            ->willReturn($user);

        $this->expectException(AuthenticationException::class);

        $this->tokenProvider->loadUserByUsername($email);
    }

    public function testWillReturnUserIfEmailExist(): void
    {
        $email = 'user@email.com';
        $user = UserData::get();

        $this->userRepositoryMock
            ->expects($this->once())
            ->method('findOneByEmailAndStatus')
            ->with($email, true)
            ->willReturn($user);

        $result = $this->tokenProvider->loadUserByUsername($email);

        $this->assertSame($user, $result);
    }

    public function testRefreshWillThrowException(): void
    {
        $user = $this->createMock(UserInterface::class);

        $this->expectException(UnsupportedUserException::class);
        $this->expectExceptionMessage('');
        $this->expectExceptionCode(0);

        $this->tokenProvider->refreshUser($user);
    }

    public function testWillReturnFalseIfClassIsNotUser(): void
    {
        $result = $this->tokenProvider->supportsClass('incorrect');

        $this->assertFalse($result);
    }

    public function testSupportWillReturnTrueIfClassIsUser(): void
    {
        $result = $this->tokenProvider->supportsClass(User::class);

        $this->assertTrue($result);
    }
}
