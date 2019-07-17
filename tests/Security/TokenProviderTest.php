<?php

declare(strict_types=1);

namespace App\Tests\Security;

use App\Repository\UserRepository;
use App\Security\TokenProvider;
use App\Tests\DataFixtures\Entity\UserData;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
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

    /**
     * @test
     *
     * @expectedException \Symfony\Component\Security\Core\Exception\AuthenticationException
     */
    public function throw_exception_when_no_user(): void
    {
        $email = 'user@email.com';
        $user = null;

        $this->userRepositoryMock
            ->expects($this->once())
            ->method('findOneByEmailAndStatus')
            ->with($email, true)
            ->willReturn($user);

        $this->tokenProvider->loadUserByUsername($email);
    }

    /**
     * @test
     */
    public function load_user_by_username_returns_user_if_give_email_exists(): void
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

    /**
     * @test
     */
    public function refresh_user_throws_exception_by_default(): void
    {
        $user = $this->createMock(UserInterface::class);

        $this->expectException(UnsupportedUserException::class);
        $this->expectExceptionMessage('');
        $this->expectExceptionCode(0);

        $this->tokenProvider->refreshUser($user);
    }

    /**
     * @test
     */
    public function supports_class_returns_false_if_parameter_is_not_user_class(): void
    {
        $result = $this->tokenProvider->supportsClass('BadClass');

        $this->assertFalse($result);
    }

    /**
     * @test
     */
    public function supports_class_returns_true_if_parameter_is_user_class(): void
    {
        $result = $this->tokenProvider->supportsClass(User::class);

        $this->assertTrue($result);
    }
}
