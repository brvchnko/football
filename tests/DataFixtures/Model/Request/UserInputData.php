<?php

declare(strict_types=1);

namespace App\Tests\DataFixtures\Model\Request;

use App\Model\Request\UserInput;

class UserInputData
{
    public static function get(): UserInput
    {
        $model = new UserInput();

        $model
            ->setEmail('test@test.test')
            ->setUsername('test')
            ->setPassword('testpw');

        return $model;
    }
}
