<?php

declare(strict_types=1);

namespace App\Tests\DataFixtures\Model\Response;

use App\Model\Response\TeamOutput;

class TeamOutputData
{
    public static function get(): TeamOutput
    {
        $model = new TeamOutput();

        $model
            ->setName('testName')
            ->setStrip('red')
            ->setLeagues([]);

        return $model;
    }
}