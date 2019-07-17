<?php

declare(strict_types=1);

namespace App\Tests\Service;

use App\DataTransformer\LeagueDataTransformer;
use App\DataTransformer\TeamDataTransformer;
use App\Repository\LeagueRepository;
use App\Service\LeagueService;
use App\Tests\DataFixtures\Entity\LeagueData;
use App\Tests\DataFixtures\Entity\TeamData;
use App\Tests\DataFixtures\Model\Request\LeagueInputData;
use App\Tests\DataFixtures\Model\Response\LeagueOutputData;
use App\Tests\DataFixtures\Model\Response\TeamOutputData;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class LeagueServiceTest extends TestCase
{
    /** @var LeagueDataTransformer|MockObject */
    private $transformer;
    /** @var TeamDataTransformer|MockObject */
    private $teamTransformer;
    /** @var LeagueRepository|MockObject */
    private $repository;
    /** @var LeagueService */
    private $sub;

    protected function setUp()
    {
        $this->transformer = $this->createMock(LeagueDataTransformer::class);
        $this->teamTransformer = $this->createMock(TeamDataTransformer::class);
        $this->repository = $this->createMock(LeagueRepository::class);
        $this->sub = new LeagueService($this->transformer, $this->repository, $this->teamTransformer);
    }

    /**
     * @test
     */
    public function willCreateLeague(): void
    {
        $league = LeagueData::get();

        $this->transformer
            ->expects($this->once())
            ->method('transformToEntity')
            ->with(LeagueInputData::get())
            ->willReturn($league);

        $this->repository
            ->expects($this->once())
            ->method('persist')
            ->with($league);

        $this->transformer
            ->expects($this->once())
            ->method('transformToModel')
            ->with($league)
            ->willReturn(LeagueOutputData::get());

        $this->sub->create(LeagueInputData::get());
    }

    /**
     * @test
     */
    public function listWillThrowException(): void
    {
        $id = 1;

        $this->repository
            ->expects($this->once())
            ->method('find')
            ->with($id)
            ->willReturn(null);

        $this->expectException(UnprocessableEntityHttpException::class);
        $this->expectExceptionMessage(sprintf('League wit %d id was not found', $id));

        $this->sub->teamList($id);
    }

    /**
     * @test
     */
    public function listWillReturnListOfTeams(): void
    {
        $league = LeagueData::get();
        $league->setTeams([TeamData::get()]);
        $id = 1;

        $this->repository
            ->expects($this->once())
            ->method('find')
            ->with($id)
            ->willReturn($league);

        $this->teamTransformer
            ->expects($this->once())
            ->method('transformToModel')
            ->with(TeamData::get())
            ->willReturn(TeamOutputData::get());

        $this->sub->teamList($id);
    }

    /**
     * @test
     */
    public function removeWillThrowException(): void
    {
        $id = 1;

        $this->repository
            ->expects($this->once())
            ->method('find')
            ->with($id)
            ->willReturn(null);

        $this->expectException(UnprocessableEntityHttpException::class);
        $this->expectExceptionMessage(sprintf('League wit %d id was not found', $id));

        $this->sub->remove($id);
    }

    /**
     * @test
     */
    public function willRemove(): void
    {
        $league = LeagueData::get();
        $id = 1;

        $this->repository
            ->expects($this->once())
            ->method('find')
            ->with($id)
            ->willReturn($league);

        $this->repository
            ->expects($this->once())
            ->method('remove')
            ->with($league);

        $this->sub->remove($id);
    }
}
