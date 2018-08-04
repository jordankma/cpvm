<?php

namespace Adtech\Core\App\Repositories;

use Adtech\Application\Cms\Repositories\Eloquent\Repository;

class ApiRepository extends Repository
{
    /**
     * @return string
     */
    public function model()
    {
        return 'Adtech\Core\App\Models\Api';
    }

    public function deleteID($id) {
        return $this->model->where('api_id', '=', $id)->update(['visible' => 0]);
    }

    public function getById($id, $columns = ['*'])
    {
        return $this->model->where('api_id', '=', $id)->first($columns);
    }

    public function findAllByPaginate($attribute, $value, $limit)
    {
        $result = $this->model->where($attribute, $value)->paginate($limit);
        return $result;
    }

    public function findAll($attribute, $value)
    {
        $result = $this->model->where($attribute, $value);
        return $result;
    }

    public function countAll()
    {
        $result = $this->model->count();
        return $result;
    }

//    public function validate($data)
//    {
//        return $this->model->validate($data);
//    }
}
