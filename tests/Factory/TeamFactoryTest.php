<?php

declare(strict_types=1);

namespace App\Tests\Factory;

use App\Factory\TeamFactory;
use App\Repository\LeagueRepository;
use App\Tests\DataFixtures\Entity\LeagueData;
use App\Tests\DataFixtures\Entity\TeamData;
use App\Tests\DataFixtures\Model\Request\TeamInputData;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class TeamFactoryTest extends TestCase
{
    /** @var LeagueRepository|MockObject */
    private $repository;
    /** @var TeamFactory */
    private $factory;

    protected function setUp()
    {
        $this->repository = $this->createMock(LeagueRepository::class);
        $this->factory = new TeamFactory($this->repository);
    }

    /**
     * @test
     */
    public function willBindNewLeagues(): void
    {
        $entity = TeamData::get();
        $leagueEntity = LeagueData::get();

        $model = TeamInputData::get();
        $model->setLeagues([1]);

        $this->repository
            ->expects($this->once())
            ->method('findAllById')
            ->with($model->getLeagues())
            ->willReturn([$leagueEntity]);

        $this->factory->bindNewLeagues($entity, $model);
    }

    /**
     * @test
     */
    public function willFailWhenTryingToBindNewEmptyLeagues(): void
    {
        $entity = TeamData::get();
        $model = TeamInputData::get();

        $this->repository
            ->expects($this->never())
            ->method('findAllById');

        $this->factory->bindNewLeagues($entity, $model);
    }

    /**
     * @test
     */
    public function willUpdateExistedLeagues(): void
    {
        $entity = TeamData::get();
        $leagueEntity = LeagueData::get();

        $model = TeamInputData::get();
        $model->setLeagues([1]);

        $this->repository
            ->expects($this->once())
            ->method('findAllById')
            ->with($model->getLeagues())
            ->willReturn([$leagueEntity]);

        $this->factory->updateExistedLeagues($entity, $model);
    }

    /**
     * @test
     */
    public function willFailWhenTryingToUpdateExistedEmptyLeagues(): void
    {
        $entity = TeamData::get();
        $model = TeamInputData::get();

        $this->repository
            ->expects($this->never())
            ->method('findAllById');

        $this->factory->updateExistedLeagues($entity, $model);
    }
}
