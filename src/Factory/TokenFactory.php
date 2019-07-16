<?php

declare(strict_types=1);

namespace App\Factory;

use App\Model\Token as Token;
use App\Entity\User;

class TokenFactory
{
    public function create(User $user, string $tokenId, int $tokenExpiry): Token\Data
    {
        $dataModel = new Token\Data();
        $metaModel = new Token\Meta();
        $userModel = new Token\User();

        $metaModel
            ->setId($tokenId)
            ->setTokenExpireTime($tokenExpiry);

        $dataModel->setMeta($metaModel);

        $userModel
            ->setId($user->getId())
            ->setEmail($user->getEmail())
            ->setRoles($user->getRoles());

        $dataModel->setUser($userModel);

        return $dataModel;
    }
}
