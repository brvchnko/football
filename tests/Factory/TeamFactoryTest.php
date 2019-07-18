<?php

declare(strict_types=1);

namespace App\Tests\Factory;

use App\Factory\LeagueFactory;
use App\Factory\TeamFactory;
use App\Tests\DataFixtures\Entity\LeagueData;
use App\Tests\DataFixtures\Entity\TeamData;
use App\Tests\DataFixtures\Model\Response\LeagueOutputData;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class TeamFactoryTest extends TestCase
{
    /** @var LeagueFactory|MockObject */
    private $leagueFactoryMock;
    /** @var TeamFactory */
    private $factory;

    protected function setUp()
    {
        $this->leagueFactoryMock = $this->createMock(LeagueFactory::class);

        $this->factory = new TeamFactory($this->leagueFactoryMock);
    }

    public function testWillCreateModelFromEntity(): void
    {
        $entity = TeamData::get();
        $entity->setLeagues([LeagueData::get()]);

        $this->leagueFactoryMock
            ->expects($this->atLeastOnce())
            ->method('createModelFromEntity')
            ->with(LeagueData::get())
            ->willReturn(LeagueOutputData::get());

        $this->factory->createModelFromEntity($entity);
    }
}
