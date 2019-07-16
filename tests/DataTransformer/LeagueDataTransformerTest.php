<?php

declare(strict_types=1);

namespace App\Tests\DataTransformer;

use App\DataTransformer\LeagueDataTransformer;
use App\Entity\League;
use App\Model\Request\LeagueInput;
use App\Model\Response\LeagueOutput;
use App\Tests\DataFixtures\Entity\LeagueData;
use App\Tests\DataFixtures\Model\Request\LeagueInputData;
use App\Tests\DataFixtures\Model\Response\LeagueOutputData;
use PHPUnit\Framework\TestCase;

class LeagueDataTransformerTest extends TestCase
{
    /** @var LeagueDataTransformer */
    private $transformer;

    protected function setUp()
    {
        $this->transformer = new LeagueDataTransformer();
    }


    /**
     * @dataProvider entityProvider
     *
     * @param League $entity
     * @param LeagueInput $model
     */
    public function testWillTransformModelToEntity(League $entity, LeagueInput $model): void
    {
        $result = $this->transformer->transformToEntity($model);
        $this->assertEquals($entity, $result);
    }

    /**
     * @dataProvider modelProvider
     *
     * @param League $entity
     * @param LeagueOutput $model
     */
    public function testWillTransformEntityToModel(League $entity, LeagueOutput $model): void
    {
        $this->assertEquals($model, $this->transformer->transformToModel($entity));
    }

    public function entityProvider(): \Generator
    {
        yield[LeagueData::get(), LeagueInputData::get()];
    }

    public function modelProvider(): \Generator
    {
        yield[LeagueData::get(), LeagueOutputData::get()];
    }
}