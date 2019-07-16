<?php

declare(strict_types=1);

namespace App\Tests\DataFixtures\Entity;

use App\Entity\League;

class LeagueData
{
    public static function get(): League
    {
        $entity = new League();

        $entity->setName('testName');

        return $entity;
    }
}