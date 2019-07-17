<?php

declare(strict_types=1);

namespace App\Tests\ParamConverters\Model;

use App\Model\Request\InputModelInterface;

class DemoModel implements InputModelInterface
{
    private $name;

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }
}
