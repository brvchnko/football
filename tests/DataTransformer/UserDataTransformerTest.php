<?php

declare(strict_types=1);

namespace App\Tests\DataTransformer;

use App\DataTransformer\UserDataTransformer;
use App\Entity\User;
use App\Model\Request\UserInput;
use App\Model\Response\UserOutput;
use App\Tests\DataFixtures\Entity\UserData;
use App\Tests\DataFixtures\Model\Request\UserInputData;
use App\Tests\DataFixtures\Model\Response\UserOutputData;
use PHPUnit\Framework\TestCase;

class UserDataTransformerTest extends TestCase
{
    /** @var UserDataTransformer */
    private $transformer;

    protected function setUp()
    {
        $this->transformer = new UserDataTransformer();
    }

    /**
     * @dataProvider entityProvider
     *
     * @param User      $entity
     * @param UserInput $model
     *
     * @test
     */
    public function willTransformModelToEntity(User $entity, UserInput $model): void
    {
        $result = $this->transformer->transformToEntity($model);
        $this->assertEquals($entity, $result);
    }

    /**
     * @dataProvider modelProvider
     *
     * @param User       $entity
     * @param UserOutput $model
     *
     * @test
     */
    public function willTransformEntityToModel(User $entity, UserOutput $model): void
    {
        $this->assertEquals($model, $this->transformer->transformToModel($entity));
    }

    public function entityProvider(): \Generator
    {
        yield[UserData::get(), UserInputData::get()];
    }

    public function modelProvider(): \Generator
    {
        yield[UserData::get(), UserOutputData::get()];
    }
}
