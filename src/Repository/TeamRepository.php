<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Team;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class TeamRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $manager)
    {
        parent::__construct($manager, Team::class);
    }

    public function persist(Team $team): void
    {
        $this->getEntityManager()->persist($team);
        $this->getEntityManager()->flush($team);
    }

    public function flush(): void
    {
        $this->getEntityManager()->flush();
    }
}