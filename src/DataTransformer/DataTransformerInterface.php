<?php

declare(strict_types=1);

namespace App\DataTransformer;

interface DataTransformerInterface
{
    /**
     * @param mixed $source
     * @param mixed $entity
     *
     * @return mixed
     */
    public function transformToEntity($source, $entity = null);

    /**
     * @param mixed $source
     *
     * @return mixed
     */
    public function transformToModel($source);
}