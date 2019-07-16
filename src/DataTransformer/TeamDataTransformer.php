<?php

declare(strict_types=1);

namespace App\DataTransformer;

use App\Entity\League;
use App\Entity\Team;
use App\Model\Request\LeagueInput;
use App\Model\Request\TeamInput;
use App\Model\Response\LeagueOutput;
use App\Model\Response\TeamOutput;

class TeamDataTransformer implements DataTransformerInterface
{
    /** @var LeagueDataTransformer  */
    private $leagueTransformer;

    public function __construct(LeagueDataTransformer $transformer)
    {
        $this->leagueTransformer = $transformer;
    }

    /**
     * @param TeamInput $source
     * @param Team $entity
     *
     * @return Team
     */
    public function transformToEntity($source, $entity = null)
    {
        if (null === $entity) {
            $entity = new Team();
        }

        $entity
            ->setName($source->getName())
            ->setStrip($source->getStrip());

        return $entity;
    }

    /**
     * @param Team $source
     *
     * @return TeamOutput
     */
    public function transformToModel($source)
    {
        $model = new TeamOutput();

        $leagues = [];
        foreach ($source->getLeagues() as $league) {
            $leagues[] = $this->leagueTransformer->transformToModel($league);
        }

        $model
            ->setId($source->getId())
            ->setLeagues($leagues)
            ->setStrip($source->getStrip())
            ->setName($source->getName());

        return $model;
    }
}