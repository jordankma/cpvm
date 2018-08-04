<?php

namespace Adtech\Core\App\Repositories\Criteria\Users;

use Adtech\Application\Cms\Repositories\Contracts\RepositoryInterface as Repository;
use Adtech\Application\Cms\Repositories\Criteria\Criteria;

class FindWhere extends Criteria
{
    /**
     * @param $model
     * @param RepositoryInterface $repository
     * @return mixed
     */
    public function apply($model, Repository $repository)
    {
        $query = $model->where('status', 1);
        return $query;
    }
}