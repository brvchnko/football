<?php

declare(strict_types=1);

namespace App\Factory;

use App\Entity\User;
use App\Model\Token\Data;
use App\Model\Token\Meta;
use App\Model\Token\User as UserModel;

class TokenFactory
{
    public function create(User $user, string $tokenId, int $tokenExpiry): Data
    {
        $metaModel = new Meta();
        $metaModel->id = $tokenId;
        $metaModel->expiry = $tokenExpiry;

        $userModel = new UserModel();
        $userModel->id = $user->getId();
        $userModel->email = $user->getEmail();
        $userModel->roles = $user->getRoles();

        $dataModel = new Data();
        $dataModel->meta = $metaModel;
        $dataModel->user = $userModel;

        return $dataModel;
    }
}
