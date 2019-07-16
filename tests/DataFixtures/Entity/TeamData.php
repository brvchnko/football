<?php

declare(strict_types=1);

namespace App\Tests\DataFixtures\Entity;

use App\Entity\Team;

class TeamData
{
    public static function get(): Team
    {
        $entity = new Team();

        $entity
            ->setName('testName')
            ->setStrip('red');

        return $entity;
    }
}