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

class ProductCriteria implements CriteriaInterface
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

        foreach ($criteria as $key => $val) {
            if ($key == 'status') $model = $model->where('status', '=', $val);

            if ($key == 'searchWord') {
                $model = $model->where(function($q) use ($criteria) {
                    foreach ($criteria['searchWord'] as $item => $value) {
                        if ($item == 0) {
                            $q->where('name', 'like','%'. $value. '%')
                                ->orWhere('description', 'like','%'. $value. '%')
                                ->orWhere('search_word', 'like','%'. $value. '%')
                                ->orWhere('freearea', 'like','%'. $value. '%');

                            continue;
                        }

                        $q->orWhere('name', 'like','%'. $value. '%')
                            ->orWhere('description', 'like','%'. $value. '%')
                            ->orWhere('search_word', 'like','%'. $value. '%')
                            ->orWhere('freearea', 'like','%'. $value. '%');
                    }
                });
            }
        }

        if (! $this->admin) $model = $model->where('status', '=', 1);

        $model = $model->orderBy('updated_at', 'desc');

        return $model;
    }

    /**
     * @return array
     */
    private function criteriaBuilder()
    {
        $criteria = array();

        if ($this->query->has('searchStatus') && !empty($this->query->get('searchStatus'))) $criteria['status'] = $this->query->get('searchStatus');

        if ($this->query->has('searchWord') && !empty($this->query->get('searchWord'))) {
            $criteria['searchWord'] = explode(' ', mb_convert_kana($this->query->get('searchWord'), 's'));
        }

        return $criteria;
    }
}
