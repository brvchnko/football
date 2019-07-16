<?php

declare(strict_types=1);

namespace App\DataTransformer;

use App\Entity\League;
use App\Model\Request\LeagueInput;
use App\Model\Response\LeagueOutput;

class LeagueDataTransformer implements DataTransformerInterface
{
    /**
     * @param LeagueInput $source
     * @param League      $entity
     *
     * @return League
     */
    public function transformToEntity($source, $entity = null)
    {
        if (null === $entity) {
            $entity = new League();
        }

        $entity->setName($source->getName());

        return $entity;
    }

    /**
     * @param League $source
     *
     * @return LeagueOutput
     */
    public function transformToModel($source)
    {
        $model = new LeagueOutput();

        $model
            ->setId($source->getId())
            ->setName($source->getName());

        return $model;
    }
}
