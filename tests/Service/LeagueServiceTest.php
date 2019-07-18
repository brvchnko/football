<?php

declare(strict_types=1);

namespace App\Tests\Service;

use App\Factory\LeagueFactory;
use App\Factory\TeamFactory;
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
    /** @var LeagueFactory|MockObject */
    private $factory;
    /** @var TeamFactory|MockObject */
    private $teamFactory;
    /** @var LeagueRepository|MockObject */
    private $repository;
    /** @var LeagueService */
    private $sub;

    protected function setUp()
    {
        $this->factory = $this->createMock(LeagueFactory::class);
        $this->teamFactory = $this->createMock(TeamFactory::class);
        $this->repository = $this->createMock(LeagueRepository::class);
        $this->sub = new LeagueService($this->factory, $this->repository, $this->teamFactory);
    }

    public function testWillCreateLeague(): void
    {
        $league = LeagueData::get();

        $this->factory
            ->expects($this->once())
            ->method('createEntityFromModel')
            ->with(LeagueInputData::get())
            ->willReturn($league);

        $this->repository
            ->expects($this->once())
            ->method('persist')
            ->with($league);

        $this->factory
            ->expects($this->once())
            ->method('createModelFromEntity')
            ->with($league)
            ->willReturn(LeagueOutputData::get());

        $this->sub->create(LeagueInputData::get());
    }

    public function testListWillThrowException(): void
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

    public function testListWillReturnListOfTeams(): void
    {
        $league = LeagueData::get();
        $league->setTeams([TeamData::get()]);
        $id = 1;

        $this->repository
            ->expects($this->once())
            ->method('find')
            ->with($id)
            ->willReturn($league);

        $this->teamFactory
            ->expects($this->once())
            ->method('createModelFromEntity')
            ->with(TeamData::get())
            ->willReturn(TeamOutputData::get());

        $this->sub->teamList($id);
    }

    public function testRemoveWillThrowException(): void
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

    public function testWillRemove(): void
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
