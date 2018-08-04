<?php

namespace Adtech\Core\App\Repositories;

use Adtech\Application\Cms\Repositories\Eloquent\Repository;

class MenuRepository extends Repository
{
    /**
     * @return string
     */
    public function model()
    {
        return 'Adtech\Core\App\Models\Menu';
    }

    public function deleteID($id) {
        return $this->model->where('menu_id', '=', $id)->update(['visible' => 0]);
    }
}