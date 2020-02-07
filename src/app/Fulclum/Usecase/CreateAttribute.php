<?php
/**
 * Created by PhpStorm.
 * User: arimoto
 * Date: 2018/05/18
 * Time: 19:21
 */

namespace App\Fulclum\Usecase;

use App\Fulclum\Repository\Repository;


class CreateAttribute
{
    private $repos;

    public function __construct(Repository $repository)
    {
        $this->repos = $repository;
    }

    /**
     * @param $array
     */
    public function run($array)
    {
        foreach ($array as $data) {
            $this->repos->create($data);
        }
    }
}