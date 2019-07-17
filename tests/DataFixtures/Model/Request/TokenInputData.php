<?php

declare(strict_types=1);

namespace App\Tests\DataFixtures\Model\Request;

use App\Model\Request\TokenInput;

class TokenInputData
{
    public static function get(): TokenInput
    {
        $model = new TokenInput();

        $model
            ->setEmail('test@test.test')
            ->setPassword('testpw');

        return $model;
    }
}
