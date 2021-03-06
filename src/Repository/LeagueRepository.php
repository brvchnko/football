<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\League;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class LeagueRepository extends ServiceEntityRepository implements LeagueRepositoryInterface
{
    public function __construct(ManagerRegistry $manager)
    {
        parent::__construct($manager, League::class);
    }

    public function persist(League $league): void
    {
        $this->getEntityManager()->persist($league);
        $this->getEntityManager()->flush();
    }

    public function remove(League $league): void
    {
        $this->getEntityManager()->remove($league);
        $this->getEntityManager()->flush();
    }

    public function findByIds(array $ids): iterable
    {
        $qb = $this->createQueryBuilder('l');

        return $qb
            ->select('l')
            ->where($qb->expr()->in('l.id', $ids))
            ->getQuery()
            ->getResult();
    }
}
