<?php

declare(strict_types=1);

namespace App\Tests\Security;

use App\Entity\User;
use App\Exceptions\TokenException;
use App\Security\JwtGuardAuthenticator;
use App\Util\TokenUtil;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class JwtGuardAuthenticatorTest extends TestCase
{
    /** @var JWTGuardAuthenticator */
    private $guard;
    /** @var TokenUtil|MockObject */
    private $util;
    /** @var Request|MockObject */
    private $request;

    protected function setUp()
    {
        $this->util = $this->createMock(TokenUtil::class);
        $this->guard = new JWTGuardAuthenticator($this->util);
        $this->request = $this->createMock(Request::class);
    }

    /**
     * @test
     */
    public function start(): void
    {
        $response = $this->guard->start($this->request);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(401, $response->getStatusCode());
    }

    /**
     * @test
     *
     * @dataProvider credentialProvider
     *
     * @param Request $request
     * @param mixed   $expected
     */
    public function getCredentials(Request $request, $expected): void
    {
        $authenticator = new JwtGuardAuthenticator($this->createMock(TokenUtil::class));

        $credentials = $authenticator->getCredentials($request);

        $this->assertEquals($expected, $credentials);
    }

    /**
     * @test
     */
    public function getUser(): void
    {
        $credentials = 'token';
        $tokenData = new \stdClass();
        $tokenData->user = new \stdClass();
        $tokenData->user->email = 'test@test.test';

        $this->util
            ->expects($this->once())
            ->method('isValid')
            ->with('token')
            ->willReturn($tokenData);

        $userProvider = $this->createMock(UserProviderInterface::class);
        $userProvider
            ->expects($this->once())
            ->method('loadUserByUsername')
            ->with('test@test.test')
            ->willReturn(new User());

        $user = $this->guard->getUser($credentials, $userProvider);

        $this->assertInstanceOf(User::class, $user);
    }

    /**
     * @test
     */
    public function getUserWithTokenException(): void
    {
        $credentials = 'token';
        $tokenData = new \stdClass();
        $tokenData->user = new \stdClass();
        $tokenData->user->email = 'test@test.test';

        $this->util
            ->method('isValid')
            ->willThrowException(new TokenException());

        $this->expectException(TokenException::class);

        $userProvider = $this->createMock(UserProviderInterface::class);

        $user = $this->guard->getUser($credentials, $userProvider);

        $this->assertNull($user);
    }

    /**
     * @test
     */
    public function getUserWithProviderWillThrowException(): void
    {
        $credentials = 'token';
        $tokenData = new \stdClass();
        $tokenData->user = new \stdClass();
        $tokenData->user->email = 'test@test.test';

        $this->util
            ->expects($this->once())
            ->method('isValid')
            ->with('token')
            ->willReturn($tokenData);

        $userProvider = $this->createMock(UserProviderInterface::class);
        $userProvider
            ->method('loadUserByUsername')
            ->willThrowException(new TokenException());

        $this->expectException(TokenException::class);

        $user = $this->guard->getUser($credentials, $userProvider);

        $this->assertNull($user);
    }

    /**
     * @test
     */
    public function onSuccess(): void
    {
        $result = $this->guard->onAuthenticationSuccess(
            $this->request,
            $this->createMock(TokenInterface::class),
            'key'
        );

        $this->assertNull($result);
    }

    /**
     * @test
     */
    public function onFailure(): void
    {
        $response = $this->guard->onAuthenticationFailure($this->request, new AuthenticationException());

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(401, $response->getStatusCode());
    }

    /**
     * @test
     */
    public function checkCredentials(): void
    {
        $result = $this->guard->checkCredentials(null, $this->createMock(UserInterface::class));

        $this->assertTrue($result);
    }

    /**
     * @test
     */
    public function rememberMeSupport(): void
    {
        $result = $this->guard->supportsRememberMe();

        $this->assertFalse($result);
    }

    public function credentialProvider(): \Generator
    {
        $request = Request::create('');
        yield [$request, null];

        $request = Request::create('', 'POST', [], [], [], ['HTTP_jwt' => 'test-string']);
        yield [$request, 'test-string'];
    }
}
