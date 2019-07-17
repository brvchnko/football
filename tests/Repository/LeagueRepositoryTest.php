<?php

declare(strict_types=1);

namespace App\Tests\Repository;

use App\Repository\LeagueRepository;
use App\Tests\DataFixtures\Entity\LeagueData;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Query\Expr;
use Doctrine\ORM\QueryBuilder;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class LeagueRepositoryTest extends TestCase
{
    /**
     * @test
     */
    public function willFindMatchesById(): void
    {
        $league = LeagueData::get();
        $queryMock = $this->createMock(AbstractQuery::class);
        $queryMock->method('getResult')->willReturn([$league]);

        /** @var QueryBuilder|MockObject $queryBuilder */
        $queryBuilder = $this->createMock(QueryBuilder::class);
        $queryBuilder->method('select')->willReturnSelf();
        $queryBuilder->method('from')->willReturnSelf();
        $queryBuilder->method('where')->willReturnSelf();
        $queryExpr = $this->createMock(Expr::class);
        $queryFunc = $this->createMock(Expr\Func::class);
        $queryBuilder->method('expr')->willReturn($queryExpr);
        $queryExpr->method('in')->willReturn($queryFunc);
        $queryBuilder->method('getQuery')->willReturn($queryMock);

        $manager = $this->createMock(EntityManager::class);
        $manager
            ->method('getClassMetadata')
            ->willReturn($this->createMock(ClassMetadata::class));
        $manager
            ->method('createQueryBuilder')
            ->willReturn($queryBuilder);

        $registry = $this->createMock(ManagerRegistry::class);
        $registry->method('getManagerForClass')->willReturn($manager);

        $repository = new LeagueRepository($registry);

        $this->assertSame([$league], $repository->findAllById([1]));
    }

    /**
     * @test
     */
    public function willPersistEntity(): void
    {
        $manager = $this->createMock(EntityManager::class);
        $manager
            ->method('getClassMetadata')
            ->willReturn($this->createMock(ClassMetadata::class));
        $manager->expects($this->once())->method('persist');
        $manager->expects($this->once())->method('flush');

        $registry = $this->createMock(ManagerRegistry::class);
        $registry->method('getManagerForClass')->willReturn($manager);

        $repository = new LeagueRepository($registry);

        $repository->persist(LeagueData::get());
    }

    /**
     * @test
     */
    public function willRemovetEntity(): void
    {
        $manager = $this->createMock(EntityManager::class);
        $manager
            ->method('getClassMetadata')
            ->willReturn($this->createMock(ClassMetadata::class));
        $manager->expects($this->once())->method('remove');
        $manager->expects($this->once())->method('flush');

        $registry = $this->createMock(ManagerRegistry::class);
        $registry->method('getManagerForClass')->willReturn($manager);

        $repository = new LeagueRepository($registry);

        $repository->remove(LeagueData::get());
    }
}
