<?php
/**
 * Created by PhpStorm.
 * User: arimoto
 * Date: 2019/01/10
 * Time: 14:47
 */

namespace App\Fulclum\Repository;
use Illuminate\Database\Eloquent\Model;

interface GetOneInterface
{
    /**
     * @param int $id
     * @return mixed
     */
    public function getOne(int $id): Model;
}