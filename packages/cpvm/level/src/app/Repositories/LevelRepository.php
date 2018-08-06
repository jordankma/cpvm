<?php

namespace Cpvm\Level\App\Repositories;

use Adtech\Application\Cms\Repositories\Eloquent\Repository;
use Illuminate\Support\Facades\DB;

/**
 * Class DemoRepository
 * @package Cpvm\Level\Repositories
 */
class LevelRepository extends Repository
{

    /**
     * @return string
     */
    public function model()
    {
        return 'Cpvm\Level\App\Models\Level';
    }

    public function findAll() {

        DB::statement(DB::raw('set @rownum=0'));
        $result = $this->model::query();
        $result->select('cpvm_level.*', DB::raw('@rownum  := @rownum  + 1 AS rownum'));

        return $result;
    }
}
