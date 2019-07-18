<?php

declare(strict_types=1);

namespace App\Tests\DataFixtures\Model\Response;

use App\Model\Response\UserOutput;

class UserOutputData
{
    public static function get(): UserOutput
    {
        $model = new UserOutput();

        $model
            ->setEmail('test@test.test')
            ->setUsername('test');

        return $model;
    }
}
