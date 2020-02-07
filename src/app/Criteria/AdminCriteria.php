<?php

namespace App\Criteria;


use Illuminate\Http\Request;
use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;
use Symfony\Component\HttpFoundation\ParameterBag;

/**
 * Class EntryCriteria.
 *
 * @package namespace App\Criteria;
 */
class AdminCriteria implements CriteriaInterface
{
    use CriteriaTrait;

    protected $query;

    public function __construct(ParameterBag $query)
    {
        $this->query = $query;
    }

    /**
     * Apply criteria in query repository
     *
     * @param string              $model
     * @param RepositoryInterface $repository
     *
     * @return mixed
     */
    public function apply($model, RepositoryInterface $repository)
    {
        $criteria = $this->criteriaBuilder();

        foreach($criteria as $key => $val) {
            if ($key == 'searchWord') {
                $model = $model->where(function($q) use ($val) {
                    $q->where('email', 'like', '%'. $val. '%')
                        ->orWhere('name', 'like', '%'. $val. '%');
                });
            }
        }

        $model = $model->orderBy('id', 'desc');
        return $model;
    }

    private function criteriaBuilder()
    {
        $criteria = array();
        if ($this->query->get('searchWord')) $criteria['searchWord'] = $this->query->get('searchWord');
        return $criteria;
    }
}
