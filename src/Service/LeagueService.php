<?php

declare(strict_types=1);

namespace App\Service;

use App\DataTransformer\LeagueDataTransformer;
use App\DataTransformer\TeamDataTransformer;
use App\Entity\League;
use App\Model\Request\LeagueInput;
use App\Model\Response\LeagueOutput;
use App\Repository\LeagueRepository;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class LeagueService
{
    /** @var LeagueDataTransformer */
    private $transformer;
    /** @var TeamDataTransformer */
    private $teamTransformer;
    /** @var LeagueRepository */
    private $repository;

    public function __construct(
        LeagueDataTransformer $transformer,
        LeagueRepository $repository,
        TeamDataTransformer $teamDataTransformer
    ) {
        $this->transformer = $transformer;
        $this->repository = $repository;
        $this->teamTransformer = $teamDataTransformer;
    }

    public function create(LeagueInput $input): LeagueOutput
    {
        $entity = $this->transformer->transformToEntity($input);

        $this->repository->persist($entity);

        return $this->transformer->transformToModel($entity);
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
            $output[] = $this->teamTransformer->transformToModel($team);
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
