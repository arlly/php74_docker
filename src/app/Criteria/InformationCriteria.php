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

class InformationCriteria implements CriteriaInterface
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
                            $q->where('title', 'like','%'. $value. '%')
                                ->orWhere('title_en', 'like','%'. $value. '%')
                                ->orWhere('note', 'like','%'. $value. '%')
                                ->orWhere('note_en', 'like','%'. $value. '%');

                            continue;
                        }

                        $q->orWhere('title', 'like','%'. $value. '%')
                            ->orWhere('title_en', 'like','%'. $value. '%')
                            ->orWhere('note', 'like','%'. $value. '%')
                            ->orWhere('note_en', 'like','%'. $value. '%');
                    }
                });
            }

            if ($key == 'archive') {
                $model = $model->where(function($q) use ($val) {
                    $q->where('published_at', '>=', $val. '-01-01')
                        ->where('published_at', '<=', $val. '-12-31');
                });
            }

            if ($key == 'information') $model = $model->whereIn('information', [$val]);
            if ($key == 'sakura') $model = $model->whereIn('sakura', [$val]);


        }

        if (!$this->admin) {
            $model = $model->where('status', '=', 1);
            $model = $model->where('published_at', '<=', date('Y-m-d'));
        }

        $model = $model->orderBy('published_at', 'desc')->orderBy('updated_at', 'desc');

        return $model;
    }

    /**
     * @return array
     */
    private function criteriaBuilder()
    {
        $criteria = array();

        if ($this->query->has('searchStatus') && !empty($this->query->get('searchStatus'))) $criteria['status'] = $this->query->get('searchStatus');
        if ($this->query->has('information') && !empty($this->query->get('information'))) $criteria['information'] = $this->query->get('information');
        if ($this->query->has('sakura') && !empty($this->query->get('sakura'))) $criteria['sakura'] = $this->query->get('sakura');
        if ($this->query->has('archive') && !empty($this->query->get('archive'))) $criteria['archive'] = $this->query->get('archive');

        if ($this->query->has('searchWord') && !empty($this->query->get('searchWord'))) {
            $criteria['searchWord'] = explode(' ', mb_convert_kana($this->query->get('searchWord'), 's'));
        }

        return $criteria;
    }
}