<?php

declare(strict_types=1);

namespace App\Tests\DataFixtures\Model\Token;

use App\Entity\User;
use App\Model\Token as TokenModel;

class TokenData
{
    public static function get(User $user, string $tokenId, int $tokenExpiry): TokenModel\Data
    {
        $dataModel = new TokenModel\Data();

        $metaModel = new TokenModel\Meta();
        $metaModel->id = $tokenId;
        $metaModel->expiry = $tokenExpiry;

        $dataModel->meta = $metaModel;

        $userModel = new TokenModel\User();
        $userModel->id = $user->getId();
        $userModel->email = $user->getEmail();
        $userModel->roles = $user->getRoles();

        $dataModel->user = $userModel;

        return $dataModel;
    }
}
