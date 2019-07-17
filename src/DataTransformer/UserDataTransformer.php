<?php

declare(strict_types=1);

namespace App\DataTransformer;

use App\Entity\User;
use App\Model\Request\UserInput;
use App\Model\Response\UserOutput;

class UserDataTransformer implements DataTransformerInterface
{
    /**
     * @param UserInput $source
     * @param User      $entity
     *
     * @return User
     */
    public function transformToEntity($source, $entity = null)
    {
        if (null === $entity) {
            $entity = new User();
        }

        $entity
            ->setEmail($source->getEmail())
            ->setUsername($source->getUsername())
            ->setPlainPassword($source->getPassword())
            ->setEnabled(true);

        return $entity;
    }

    /**
     * @param User $source
     *
     * @return UserOutput
     */
    public function transformToModel($source)
    {
        $outputModel = new UserOutput();

        $outputModel
            ->setUsername($source->getUsername())
            ->setEmail($source->getEmail());

        return $outputModel;
    }
}
