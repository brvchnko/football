<?php

declare(strict_types=1);

namespace App\Service;

use App\DataTransformer\TeamDataTransformer;
use App\Factory\TeamFactory;
use App\Model\Request\TeamInput;
use App\Model\Response\TeamOutput;
use App\Repository\LeagueRepository;
use App\Repository\TeamRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class TeamService
{
    /** @var TeamDataTransformer */
    private $transformer;
    /** @var TeamRepository */
    private $repository;
    /** @var TeamFactory */
    private $factory;

    public function __construct(
        TeamDataTransformer $transformer,
        TeamRepository $repository,
        TeamFactory $factory
    ) {
        $this->transformer = $transformer;
        $this->factory = $factory;
        $this->repository = $repository;
    }

    public function create(TeamInput $input): TeamOutput
    {
        $entity = $this->transformer->transformToEntity($input);

        $this->factory->bindNewLeagues($entity, $input);

        $this->repository->persist($entity);

        return $this->transformer->transformToModel($entity);
    }

    public function replace(TeamInput $input, int $id): TeamOutput
    {
        $teamEntity = $this->repository->find($id);

        if (null === $teamEntity) {
            throw new UnprocessableEntityHttpException(sprintf('Team wit %d id was not found', $id));
        }

        $entity = $this->transformer->transformToEntity($input, $teamEntity);

        $this->factory->updateExistedLeagues($entity, $input);

        $this->repository->flush();

        return $this->transformer->transformToModel($entity);
    }
}