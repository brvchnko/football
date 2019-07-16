<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $manager)
    {
        parent::__construct($manager, User::class);
    }

    public function findOneByEmailAndStatus(string $email, bool $enabled = true): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.email = :email')
            ->andWhere('u.enabled = :enabled')
            ->setParameters(['email' => $email, 'enabled' => $enabled])
            ->getQuery()
            ->getOneOrNullResult();
    }
}
