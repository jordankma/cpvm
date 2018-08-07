<?php

namespace Cpvm\Subject\App\Repositories;

use Adtech\Application\Cms\Repositories\Eloquent\Repository;
use Illuminate\Support\Facades\DB;

/**
 * Class DemoRepository
 * @package Cpvm\Subject\Repositories
 */
class SubjectRepository extends Repository
{

    /**
     * @return string
     */
    public function model()
    {
        return 'Cpvm\Subject\App\Models\Subject';
    }

    public function findAll() {

        DB::statement(DB::raw('set @rownum=0'));
        $result = $this->model::query()->with('getClass');
        $result->select('subject.*', DB::raw('@rownum  := @rownum  + 1 AS rownum'));

        return $result;
    }
}
