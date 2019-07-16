<?php

declare(strict_types=1);

namespace App\Tests\DataFixtures\Model\Response;

use App\Model\Response\LeagueOutput;

class LeagueOutputData
{
    public static function get(): LeagueOutput
    {
        $model = new LeagueOutput();

        $model->setName('testName');

        return $model;
    }
}
