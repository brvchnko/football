<?php

declare(strict_types=1);

namespace App\Factory;

use App\Entity\League;
use App\Model\Request\LeagueInput;
use App\Model\Response\LeagueOutput;

class LeagueFactory
{
    public function createModelFromEntity(League $entity): LeagueOutput
    {
        $model = new LeagueOutput();

        $model
            ->setId($entity->getId())
            ->setName($entity->getName());

        return $model;
    }

    public function createEntityFromModel(LeagueInput $model, League $entity = null): League
    {
        if (null === $entity) {
            $entity = new League();
        }

        $entity->setName($model->getName());

        return $entity;
    }
}
