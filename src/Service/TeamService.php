<?php

declare(strict_types=1);

namespace App\Service;

use App\Factory\TeamFactory;
use App\Model\Request\TeamInput;
use App\Model\Response\TeamOutput;
use App\Repository\LeagueRepositoryInterface;
use App\Repository\TeamRepository;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class TeamService
{
    /** @var TeamRepository */
    private $repository;
    /** @var TeamFactory */
    private $factory;
    /** @var LeagueRepositoryInterface */
    private $leagueRepository;

    public function __construct(
        TeamRepository $repository,
        TeamFactory $factory,
        LeagueRepositoryInterface $leagueRepository
    ) {
        $this->leagueRepository = $leagueRepository;
        $this->factory = $factory;
        $this->repository = $repository;
    }

    public function create(TeamInput $input): TeamOutput
    {
        $entity = $this->factory->createEntityFromModel($input);

        if (empty($input->getLeagues())) {
            $leagues = $this->leagueRepository->findAllById($input->getLeagues());

            $this->factory->bindNewLeagues($entity, $leagues);
        }

        $this->repository->persist($entity);

        return $this->factory->createModelFromEntity($entity);
    }

    public function replace(TeamInput $input, int $id): TeamOutput
    {
        $teamEntity = $this->repository->find($id);

        if (null === $teamEntity) {
            throw new UnprocessableEntityHttpException(sprintf('Team wit %d id was not found', $id));
        }

        $entity = $this->factory->createEntityFromModel($input, $teamEntity);

        if (empty($input->getLeagues())) {
            $leagues = $this->leagueRepository->findAllById($input->getLeagues());

            $this->factory->updateExistedLeagues($entity, $leagues);
        }

        $this->repository->flush();

        return $this->factory->createModelFromEntity($entity);
    }
}
