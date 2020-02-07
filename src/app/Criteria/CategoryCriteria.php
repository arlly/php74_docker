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

class CategoryCriteria implements CriteriaInterface
{
    use CriteriaTrait;

    protected $query;
    protected $admin;

    public function __construct(ParameterBag $query, $admin = true)
    {
        $this->query = $query;
        $this->admin = $admin;
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
            if ($key == 'category_id') $model = $model->where('category_id', '=', $val);
        }

        $model = $model->orderBy('sort_number', 'asc');

        return $model;
    }

    /**
     * @return array
     */
    private function criteriaBuilder()
    {
        $criteria = [];

        if ($this->query->has('category_id') && !empty($this->query->get('category_id'))) $criteria['category_id'] = $this->query->get('category_id');

        return $criteria;
    }
}