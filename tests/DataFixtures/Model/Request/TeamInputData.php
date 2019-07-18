<?php

declare(strict_types=1);

namespace App\Tests\DataFixtures\Model\Request;

use App\Model\Request\TeamInput;

class TeamInputData
{
    public static function get(): TeamInput
    {
        $model = new TeamInput();

        $model
            ->setName('testName')
            ->setStrip('red');

        return $model;
    }
}
