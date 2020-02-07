<?php
/**
 * Created by PhpStorm.
 * User: arimoto
 * Date: 2019/01/10
 * Time: 17:25
 */

namespace App\Fulclum\Usecase;


use App\Fulclum\Repository\GetOneInterface;

class GetOneAvtiveRow
{
    protected $repos;

    public function __construct(GetOneInterface $repos)
    {
        $this->repos = $repos;
    }

    /**
     * @param int $id
     * @return \Illuminate\Database\Eloquent\Model|mixed
     * @throws \Exception
     */
    public function run(int $id)
    {
        return  $this->repos->getOne($id);
    }

}