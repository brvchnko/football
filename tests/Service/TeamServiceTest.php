<?php

declare(strict_types=1);

namespace App\Tests\Service;

use App\Factory\TeamFactory;
use App\Repository\LeagueRepositoryInterface;
use App\Repository\TeamRepository;
use App\Service\TeamService;
use App\Tests\DataFixtures\Entity\LeagueData;
use App\Tests\DataFixtures\Entity\TeamData;
use App\Tests\DataFixtures\Model\Request\TeamInputData;
use App\Tests\DataFixtures\Model\Response\TeamOutputData;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Bundle\FrameworkBundle\Tests\TestCase;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class TeamServiceTest extends TestCase
{
    /** @var LeagueRepositoryInterface|MockObject */
    private $leagueRepository;
    /** @var TeamRepository|MockObject */
    private $repository;
    /** @var TeamFactory|MockObject */
    private $factory;
    /** @var TeamService */
    private $sub;

    protected function setUp()
    {
        $this->leagueRepository = $this->createMock(LeagueRepositoryInterface::class);
        $this->repository = $this->createMock(TeamRepository::class);
        $this->factory = $this->createMock(TeamFactory::class);
        $this->sub = new TeamService($this->repository, $this->factory, $this->leagueRepository);
    }

    public function testCreateTeamWithoutLeagues(): void
    {
        $team = TeamData::get();
        $model = TeamInputData::get();

        $this->factory
            ->expects($this->once())
            ->method('createEntityFromModel')
            ->with($model)
            ->willReturn($team);

        $this->repository
            ->expects($this->once())
            ->method('persist')
            ->with($team);

        $this->factory
            ->expects($this->once())
            ->method('createModelFromEntity')
            ->with($team)
            ->willReturn(TeamOutputData::get());

        $this->sub->create($model);
    }

    public function testCreateTeamWithLeagues(): void
    {
        $team = TeamData::get();

        $model = TeamInputData::get();
        $model->setLeagues([1]);

        $this->factory
            ->expects($this->once())
            ->method('createEntityFromModel')
            ->with($model)
            ->willReturn($team);

        $this->leagueRepository
            ->expects($this->once())
            ->method('findByIds')
            ->with($model->getLeagues())
            ->willReturn([LeagueData::get()]);

        $this->factory
            ->expects($this->once())
            ->method('bindNewLeagues')
            ->with($team, [LeagueData::get()]);

        $this->repository
            ->expects($this->once())
            ->method('persist')
            ->with($team);

        $this->factory
            ->expects($this->once())
            ->method('createModelFromEntity')
            ->with($team)
            ->willReturn(TeamOutputData::get());

        $this->sub->create($model);
    }

    public function testRemoveWillThrowException(): void
    {
        $id = 1;
        $model = TeamInputData::get();

        $this->repository
            ->expects($this->once())
            ->method('find')
            ->with($id)
            ->willReturn(null);

        $this->expectException(UnprocessableEntityHttpException::class);
        $this->expectExceptionMessage(sprintf('Team wit %d id was not found', $id));

        $this->sub->replace($model, $id);
    }

    public function testWillReplaceeWithoutLeagoues(): void
    {
        $team = TeamData::get();
        $model = TeamInputData::get();
        $id = 1;

        $this->repository
            ->expects($this->once())
            ->method('find')
            ->with($id)
            ->willReturn($team);

        $this->factory
            ->expects($this->once())
            ->method('createEntityFromModel')
            ->with($model, $team)
            ->willReturn($team);

        $this->repository
            ->expects($this->once())
            ->method('flush');

        $this->factory
            ->expects($this->once())
            ->method('createModelFromEntity')
            ->with($team)
            ->willReturn(TeamOutputData::get());

        $this->sub->replace($model, $id);
    }

    public function testWillReplaceWithLeagoues(): void
    {
        $team = TeamData::get();
        $model = TeamInputData::get();
        $model->setLeagues([1]);
        $id = 1;

        $this->repository
            ->expects($this->once())
            ->method('find')
            ->with($id)
            ->willReturn($team);

        $this->factory
            ->expects($this->once())
            ->method('createEntityFromModel')
            ->with($model, $team)
            ->willReturn($team);

        $this->leagueRepository
            ->expects($this->once())
            ->method('findByIds')
            ->with($model->getLeagues())
            ->willReturn([LeagueData::get()]);

        $this->factory
            ->expects($this->once())
            ->method('updateExistedLeagues')
            ->with($team, [LeagueData::get()]);

        $this->repository
            ->expects($this->once())
            ->method('flush');

        $this->factory
            ->expects($this->once())
            ->method('createModelFromEntity')
            ->with($team)
            ->willReturn(TeamOutputData::get());

        $this->sub->replace($model, $id);
    }
}
