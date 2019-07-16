<?php

declare(strict_types=1);

namespace App\Tests\DataTransformer;

use App\DataTransformer\LeagueDataTransformer;
use App\DataTransformer\TeamDataTransformer;
use App\Entity\League;
use App\Entity\Team;
use App\Model\Request\LeagueInput;
use App\Model\Request\TeamInput;
use App\Model\Response\LeagueOutput;
use App\Model\Response\TeamOutput;
use App\Tests\DataFixtures\Entity\LeagueData;
use App\Tests\DataFixtures\Entity\TeamData;
use App\Tests\DataFixtures\Model\Request\LeagueInputData;
use App\Tests\DataFixtures\Model\Request\TeamInputData;
use App\Tests\DataFixtures\Model\Response\LeagueOutputData;
use App\Tests\DataFixtures\Model\Response\TeamOutputData;
use PHPUnit\Framework\TestCase;

class TeamDataTransformerTest extends TestCase
{
    /** @var LeagueDataTransformer */
    private $leagueTransformer;
    /** @var TeamDataTransformer */
    private $transformer;

    protected function setUp()
    {
        $this->leagueTransformer = $this->createMock(LeagueDataTransformer::class);
        $this->transformer = new TeamDataTransformer($this->leagueTransformer);
    }

    /**
     * @dataProvider entityProvider
     *
     * @param Team $entity
     * @param TeamInput $model
     */
    public function testWillTransformModelToEntity(Team $entity, TeamInput $model): void
    {
        $result = $this->transformer->transformToEntity($model);
        $this->assertEquals($entity, $result);
    }

    /**
     * @dataProvider modelProvider
     *
     * @param Team $entity
     * @param TeamOutput $model
     */
    public function testWillTransformEntityToModel(Team $entity, TeamOutput $model): void
    {
        $this->assertEquals($model, $this->transformer->transformToModel($entity));
    }

    public function entityProvider(): \Generator
    {
        yield[TeamData::get(), TeamInputData::get()];
    }

    public function modelProvider(): \Generator
    {
        yield[TeamData::get(), TeamOutputData::get()];
    }
}