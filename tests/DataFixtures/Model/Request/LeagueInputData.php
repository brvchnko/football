<?php


namespace App\Tests\DataFixtures\Model\Request;


use App\Model\Request\LeagueInput;

class LeagueInputData
{

    public static function get(): LeagueInput
    {
        $model = new LeagueInput();

        $model->setName('testName');

        return $model;
    }
}