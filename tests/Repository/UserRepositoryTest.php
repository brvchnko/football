<?php

declare(strict_types=1);

namespace App\Tests\Repository;

use App\Repository\UserRepository;
use App\Tests\DataFixtures\Entity\UserData;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\QueryBuilder;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class UserRepositoryTest extends TestCase
{
    public function testWillFindByEmailAndStatus(): void
    {
        $user = UserData::get();
        $queryMock = $this->createMock(AbstractQuery::class);
        $queryMock->method('getOneOrNullResult')->willReturn($user);

        /** @var QueryBuilder|MockObject $queryBuilder */
        $queryBuilder = $this->createMock(QueryBuilder::class);
        $queryBuilder->method('select')->willReturnSelf();
        $queryBuilder->method('from')->willReturnSelf();
        $queryBuilder->method('andWhere')->willReturnSelf();
        $queryBuilder->method('setParameters')->willReturnSelf();
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

        $repository = new UserRepository($registry);

        $this->assertSame($user, $repository->findOneByEmailAndStatus('test@test.test'));
    }
}
