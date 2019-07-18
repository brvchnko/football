<?php

declare(strict_types=1);

namespace App\Security;

use App\Exceptions\TokenException;
use App\Util\TokenUtil;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class JWTGuardAuthenticator extends AbstractGuardAuthenticator
{
    /**
     * @var TokenUtil
     */
    private $tokenUtil;

    public function __construct(TokenUtil $tokenUtil)
    {
        $this->tokenUtil = $tokenUtil;
    }

    public function start(Request $request, AuthenticationException $authException = null)
    {
        return new Response(null, Response::HTTP_UNAUTHORIZED);
    }

    public function supports(Request $request)
    {
        return $request->headers->has('jwt');
    }

    public function getCredentials(Request $request)
    {
        return $request->headers->get('jwt');
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        try {
            $tokenData = $this->tokenUtil->isValid($credentials);
        } catch (TokenException $exception) {
            throw $exception;
        }

        $userId = $tokenData->user->email;

        try {
            $user = $userProvider->loadUserByUsername($userId);
        } catch (AuthenticationException $exception) {
            throw new TokenException();
        }

        return $user ?? null;
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        return true;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        return new Response(null, Response::HTTP_UNAUTHORIZED);
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        return null;
    }

    public function supportsRememberMe()
    {
        return false;
    }
}
