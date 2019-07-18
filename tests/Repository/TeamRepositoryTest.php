<?php

declare(strict_types=1);

namespace App\Tests\Repository;

use App\Repository\TeamRepository;
use App\Tests\DataFixtures\Entity\TeamData;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\ClassMetadata;
use PHPUnit\Framework\TestCase;

class TeamRepositoryTest extends TestCase
{
    public function testWillPersistEntity(): void
    {
        $manager = $this->createMock(EntityManager::class);
        $manager
            ->method('getClassMetadata')
            ->willReturn($this->createMock(ClassMetadata::class));
        $manager->expects($this->once())->method('persist');
        $manager->expects($this->once())->method('flush');

        $registry = $this->createMock(ManagerRegistry::class);
        $registry->method('getManagerForClass')->willReturn($manager);

        $repository = new TeamRepository($registry);

        $repository->persist(TeamData::get());
    }

    public function testWillFlushEntity(): void
    {
        $manager = $this->createMock(EntityManager::class);
        $manager
            ->method('getClassMetadata')
            ->willReturn($this->createMock(ClassMetadata::class));
        $manager->expects($this->once())->method('flush');

        $registry = $this->createMock(ManagerRegistry::class);
        $registry->method('getManagerForClass')->willReturn($manager);

        $repository = new TeamRepository($registry);

        $repository->flush(TeamData::get());
    }
}
