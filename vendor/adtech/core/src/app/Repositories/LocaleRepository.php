<?php

namespace Adtech\Core\App\Repositories;

use Adtech\Application\Cms\Repositories\Eloquent\Repository;

class LocaleRepository extends Repository
{
    /**
     * @return string
     */
    public function model()
    {
        return 'Adtech\Core\App\Models\Locale';
    }

    public function findAll($domain_id = 0) {
        $result = $this->model::query();
        $result->where('domain_id', $domain_id);
        return $result;
    }
}
