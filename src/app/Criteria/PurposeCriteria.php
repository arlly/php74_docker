<?php
/**
 * Created by PhpStorm.
 * User: arimoto
 * Date: 2018/02/05
 * Time: 11:29
 */

namespace App\Criteria;

use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;
use Symfony\Component\HttpFoundation\ParameterBag;

class PurposeCriteria implements CriteriaInterface
{
    use CriteriaTrait;

    protected $query;
    protected $admin;

    public function __construct(ParameterBag $query, $admin = true)
    {
        $this->query = $query;
        $this->admin = $admin;

        $this->orderByColumn = "created_at";
        $this->direction = "desc";
    }

    /**
     * Apply criteria in query repository
     *
     * @param                     $model
     * @param RepositoryInterface $repository
     *
     * @return mixed
     */
    public function apply($model, RepositoryInterface $repository)
    {
        $criteria = $this->criteriaBuilder();

        foreach($criteria as $key => $val) {
            if ($key == 'status') $model = $model->where('status', '=', $val);
            if ($key == 'category_id') $model = $model->where('category_id', '=', $val);

            if ($key == 'searchWord') {
                $model = $model->where(function($q) use ($val) {
                    $q->where('title', 'like', '%'. $val. '%')
                        ->orWhere('body', 'like', '%'. $val. '%');
                });
            }

            if ($key == 'searchContents') {
                $model = $model->where(function($q) use ($val) {
                    $q->where('title', 'like', '%'. $val. '%')
                        ->orWhere('contents', 'like', '%'. $val. '%');
                });
            }

            if ($key == 'archive') {
                $model = $model->where(function($q) use ($val) {
                    $q->where('published_at', '>=', $val. '-01-01')
                        ->where('published_at', '<=', $val. '-12-31');
                });
            }

            if ($key == 'create_date1') $model = $model->where('created_at', '>=', $val. "00:00:00");
            if ($key == 'create_date2') $model = $model->where('created_at', '<=', $val. "23:59:59");
            if ($key == 'file_type') $model = $model->where('file_type', '=', $val);

        }

        if (! $this->admin) {
            $model = $model->where('status', '=', 1);
            $model = $model->where('published_at', '<=', date('Y-m-d'));

        }

        if (! $this->admin) {
            $model = $model->orderBy('published_at', 'desc');
        } else {
            if ($this->query->has('orderBy')) {
                $this->orderByColumn = $this->query->get('orderBy');
                $this->direction = $this->query->get('sortedBy');
            }
            $model = $model->orderBy($this->orderByColumn, $this->direction);
        }

        return $model;
    }

    /**
     * @return array
     */
    private function criteriaBuilder()
    {
        $criteria = array();

        if ($this->query->has('searchStatus') && !empty($this->query->get('searchStatus'))) $criteria['status'] = $this->query->get('searchStatus');
        if ($this->query->has('searchStatus') && !empty($this->query->get('searchStatus'))) $criteria['status'] = $this->query->get('searchStatus');
        if ($this->query->has('searchStatus') && !empty($this->query->get('searchStatus'))) $criteria['status'] = $this->query->get('searchStatus');

        if ($this->query->has('searchStatus') && !empty($this->query->get('searchStatus'))) $criteria['status'] = $this->query->get('searchStatus');
        if ($this->query->has('searchWord') && !empty($this->query->get('searchWord'))) $criteria['searchWord'] = $this->query->get('searchWord');
        if ($this->query->has('searchContents') && !empty($this->query->get('searchContents'))) $criteria['searchContents'] = $this->query->get('searchContents');
        if ($this->query->has('archive') && !empty($this->query->get('archive'))) $criteria['archive'] = $this->query->get('archive');
        if ($this->query->has('category_id') && !empty($this->query->get('category_id'))) $criteria['category_id'] = $this->query->get('category_id');

        return $criteria;
    }
}