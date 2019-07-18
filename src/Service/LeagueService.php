<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\League;
use App\Factory\LeagueFactory;
use App\Factory\TeamFactory;
use App\Model\Request\LeagueInput;
use App\Model\Response\LeagueOutput;
use App\Repository\LeagueRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class LeagueService
{
    /** @var LeagueFactory */
    private $leagueFactory;
    /** @var TeamFactory */
    private $teamFactory;
    /** @var LeagueRepositoryInterface */
    private $repository;

    public function __construct(
        LeagueFactory $leagueFactory,
        LeagueRepositoryInterface $repository,
        TeamFactory $teamFactory
    ) {
        $this->leagueFactory = $leagueFactory;
        $this->repository = $repository;
        $this->teamFactory = $teamFactory;
    }

    public function create(LeagueInput $input): LeagueOutput
    {
        $entity = $this->leagueFactory->createEntityFromModel($input);

        $this->repository->persist($entity);

        return $this->leagueFactory->createModelFromEntity($entity);
    }

    public function teamList(int $id): array
    {
        /** @var League $entity */
        $entity = $this->repository->find($id);

        if (null === $entity) {
            throw new UnprocessableEntityHttpException(sprintf('League wit %d id was not found', $id));
        }

        $output = [];
        foreach ($entity->getTeams()->getValues() as $team) {
            $output[] = $this->teamFactory->createModelFromEntity($team);
        }

        return $output;
    }

    public function remove(int $id): void
    {
        $entity = $this->repository->find($id);

        if (null === $entity) {
            throw new UnprocessableEntityHttpException(sprintf('League wit %d id was not found', $id));
        }

        $this->repository->remove($entity);
    }
}
