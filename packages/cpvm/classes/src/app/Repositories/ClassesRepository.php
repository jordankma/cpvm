<?php

namespace Cpvm\Classes\App\Repositories;

use Adtech\Application\Cms\Repositories\Eloquent\Repository;
use Illuminate\Support\Facades\DB;

/**
 * Class ClassesRepository
 * @package Cpvm\Classes\Repositories
 */
class ClassesRepository extends Repository
{

    /**
     * @return string
     */
    public function model()
    {
        return 'Cpvm\Classes\App\Models\Classes';
    }

    public function findAll() {

        DB::statement(DB::raw('set @rownum=0'));
        $result = $this->model::query();
        $result->select('classes.*', DB::raw('@rownum  := @rownum  + 1 AS rownum'));

        return $result;
    }

}
