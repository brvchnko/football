<?php

declare(strict_types=1);

namespace App\Factory;

use App\Entity\Team;
use App\Model\Request\TeamInput;
use App\Repository\LeagueRepository;

class TeamFactory
{
    /** @var LeagueRepository */
    private $leagueRepository;

    public function __construct(LeagueRepository $leagueRepository)
    {
        $this->leagueRepository = $leagueRepository;
    }

    public function bindNewLeagues(Team $entity, TeamInput $input): void
    {
        if (empty($input->getLeagues())) {
            return;
        }

        $leagues = $this->leagueRepository->findAllById($input->getLeagues());
        foreach ($leagues as $league) {
            $entity->addLeague($league);
        }
    }

    public function updateExistedLeagues(Team $entity, TeamInput $input): void
    {
        if (empty($input->getLeagues())) {
            return;
        }

        $leagues = $this->leagueRepository->findAllById($input->getLeagues());

        $entity->setLeagues($leagues);
    }
}