<?php
/**
 * Created by PhpStorm.
 * User: arimoto
 * Date: 2018/09/07
 * Time: 9:54
 */
namespace App\Repositories;

use App\Eloquent\Information;
use App\Fulclum\Repository\GetOneInterface;
use App\Fulclum\Repository\Repository;
use Illuminate\Database\Eloquent\Model;

class InformationRepository extends Repository implements GetOneInterface
{

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Information::class;
    }


    /**
     * @return mixed
     */
    public function getToppageList()
    {
        return $this->scopeQuery(function ($query){
            return $query->where('status', 1)
                ->where('published_at', '<=', date('Y-m-d'))
                ->where('information', 1)
                ->orderBy('published_at', 'desc')
                ->orderBy('updated_at', 'desc');
        })->paginate(5);
    }

    /**
     * @return mixed
     */
    public function getSakuraToppageList()
    {
        return $this->scopeQuery(function ($query){
            return $query->where('status', 1)
                ->where('published_at', '<=', date('Y-m-d'))
                ->where('sakura', 1)
                ->orderBy('published_at', 'desc')
                ->orderBy('updated_at', 'desc');
        })->paginate(5);
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function getOne(int $id): Model
    {
        $row =  $this->findWhere(
            [
                'id' => $id,
                'status' => 1,
                ['published_at', '<=', date('Y-m-d H:i:s')],
            ]
        )->first();

        if (is_null($row)) abort(404);

        return $row;
    }
}