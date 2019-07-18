<?php

declare(strict_types=1);

namespace App\Util;

use App\Entity\User;
use App\Exceptions\TokenException;
use App\Factory\TokenFactory;
use Exception;
use Firebase\JWT\JWT;
use Predis\ClientInterface;
use stdClass;

class TokenUtil
{
    private $jwt;
    private $cache;
    private $secret;
    private $factory;
    private $tokenLife;
    private $algorithm;
    private $cacheTime;

    public function __construct(
        JWT $jwt,
        ClientInterface $cache,
        TokenFactory $factory,
        int $tokenLife,
        string $algorithm,
        int $cacheTime,
        string $secret
    ) {
        $this->jwt = $jwt;
        $this->cache = $cache;
        $this->factory = $factory;
        $this->tokenLife = $tokenLife;
        $this->algorithm = $algorithm;
        $this->cacheTime = $cacheTime;
        $this->secret = $secret;
    }

    public function create(User $user)
    {
        $time = time();
        $tokenId = sha1($time.$user->getId());
        $tokenExpiry = $time + $this->tokenLife;
        $tokenData = $this->factory->create($user, $tokenId, $tokenExpiry);
        $token = $this->jwt::encode($tokenData, $this->secret, $this->algorithm);
        $this->save($user->getId(), $tokenExpiry, $tokenId);

        return $token;
    }

    public function isTokenInCache(int $userId, string $tokenId): void
    {
        $cacheData = $this->cache->get($userId);
        if (!$cacheData) {
            throw new TokenException();
        }

        if ($cacheData !== $tokenId) {
            throw new TokenException();
        }
    }

    public function isValid(?string $token = null)
    {
        $tokenData = $this->getTokenData($token);

        if ($tokenData->meta->expiry < time()) {
            throw new TokenException();
        }

        $this->isTokenInCache($tokenData->user->id, $tokenData->meta->id);

        return $tokenData;
    }

    public function getTokenData(?string $token): stdClass
    {
        try {
            $tokenData = $this->jwt::decode($token, $this->secret, [$this->algorithm]);
        } catch (Exception $e) {
            throw new TokenException();
        }

        return $tokenData;
    }

    private function save(int $id, int $expiry, string $value): void
    {
        $this->cache->setex($id, $expiry + $this->cacheTime, $value);
    }
}
