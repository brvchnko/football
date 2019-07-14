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
    public function transform($source, $entity = null);

    /**
     * @param mixed $source
     *
     * @return mixed
     */
    public function reverseTransform($source);
}