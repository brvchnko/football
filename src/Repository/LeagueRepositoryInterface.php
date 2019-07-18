<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\League;
use Doctrine\Common\Persistence\ObjectRepository;

interface LeagueRepositoryInterface extends ObjectRepository
{
    public function persist(League $league): void;

    public function remove(League $league): void;

    public function findByIds(array $ids): iterable;
}
