<?php
/**
 * Created by PhpStorm.
 * User: arimoto
 * Date: 2018/02/02
 * Time: 16:17
 */

namespace App\Fulclum\Usecase;

use App\Fulclum\Repository\Repository;

class Search
{
    private $repo;
    private $limit;

    public function __construct(Repository $repo, $limit = 24)
    {
        $this->repo = $repo;
        $this->limit = $limit;
    }

    public function run($criteria)
    {
        $this->repo->pushCriteria($criteria);
        return $this->repo->paginate($this->limit);
    }

}