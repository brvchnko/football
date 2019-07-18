<?php

declare(strict_types=1);

namespace App\Factory;

use App\Entity\Team;
use App\Model\Request\TeamInput;
use App\Model\Response\TeamOutput;

class TeamFactory
{
    /** @var LeagueFactory */
    private $leagueFactory;

    public function __construct(LeagueFactory $leagueFactory)
    {
        $this->leagueFactory = $leagueFactory;
    }

    public function createModelFromEntity(Team $entity): TeamOutput
    {
        $model = new TeamOutput();

        $leagues = [];
        foreach ($entity->getLeagues() as $league) {
            $leagues[] = $this->leagueFactory->createModelFromEntity($league);
        }

        $model
            ->setId($entity->getId())
            ->setLeagues($leagues)
            ->setStrip($entity->getStrip())
            ->setName($entity->getName());

        return $model;
    }

    public function createEntityFromModel(TeamInput $model, Team $entity = null): Team
    {
        if (null === $entity) {
            $entity = new Team();
        }

        $entity
            ->setName($model->getName())
            ->setStrip($model->getStrip());

        return $entity;
    }

    public function bindNewLeagues(Team $entity, iterable $leagues): void
    {
        foreach ($leagues as $league) {
            $entity->addLeague($league);
        }
    }

    public function updateExistedLeagues(Team $entity, iterable $leagues): void
    {
        $entity->setLeagues($leagues);
    }
}
