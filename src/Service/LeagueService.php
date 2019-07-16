<?php

declare(strict_types=1);

namespace App\Service;

use App\DataTransformer\LeagueDataTransformer;
use App\Model\Request\LeagueInput;
use App\Model\Response\LeagueOutput;
use App\Repository\LeagueRepository;

class LeagueService
{
    /** @var LeagueDataTransformer */
    private $transformer;
    /** @var LeagueRepository */
    private $repository;

    public function __construct(LeagueDataTransformer $transformer, LeagueRepository $repository)
    {
        $this->transformer = $transformer;
        $this->repository = $repository;
    }

    public function create(LeagueInput $input): LeagueOutput
    {
        $entity = $this->transformer->transformToEntity($input);

        $this->repository->persist($entity);

        return $this->transformer->transformToModel($entity);
    }
}