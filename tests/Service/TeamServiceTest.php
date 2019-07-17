<?php

declare(strict_types=1);

namespace App\Tests\Service;

use App\DataTransformer\TeamDataTransformer;
use App\Factory\TeamFactory;
use App\Repository\TeamRepository;
use App\Service\TeamService;
use App\Tests\DataFixtures\Entity\TeamData;
use App\Tests\DataFixtures\Model\Request\TeamInputData;
use App\Tests\DataFixtures\Model\Response\TeamOutputData;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Bundle\FrameworkBundle\Tests\TestCase;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class TeamServiceTest extends TestCase
{
    /** @var TeamDataTransformer|MockObject */
    private $transformer;
    /** @var TeamRepository|MockObject */
    private $repository;
    /** @var TeamFactory|MockObject */
    private $factory;
    /** @var TeamService */
    private $sub;

    protected function setUp()
    {
        $this->transformer = $this->createMock(TeamDataTransformer::class);
        $this->repository = $this->createMock(TeamRepository::class);
        $this->factory = $this->createMock(TeamFactory::class);
        $this->sub = new TeamService($this->transformer, $this->repository, $this->factory);
    }

    public function willCreateTeam(): void
    {
        $team = TeamData::get();
        $model = TeamInputData::get();

        $this->transformer
            ->expects($this->once())
            ->method('transformToEntity')
            ->with($model)
            ->willReturn($team);

        $this->factory
            ->expects($this->once())
            ->method('bindNewLeagues')
            ->with($team, $model);

        $this->repository
            ->expects($this->once())
            ->method('persist')
            ->with($team);

        $this->transformer
            ->expects($this->once())
            ->method('transformToModel')
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

    public function testWillRemove(): void
    {
        $team = TeamData::get();
        $model = TeamInputData::get();
        $id = 1;

        $this->repository
            ->expects($this->once())
            ->method('find')
            ->with($id)
            ->willReturn($team);

        $this->transformer
            ->expects($this->once())
            ->method('transformToEntity')
            ->with($model, $team)
            ->willReturn($team);

        $this->factory
            ->expects($this->once())
            ->method('updateExistedLeagues')
            ->with($team, $model);

        $this->repository
            ->expects($this->once())
            ->method('flush');

        $this->transformer
            ->expects($this->once())
            ->method('transformToModel')
            ->with($team)
            ->willReturn(TeamOutputData::get());

        $this->sub->replace($model, $id);
    }


}