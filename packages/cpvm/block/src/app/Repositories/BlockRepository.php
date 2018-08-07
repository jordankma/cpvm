<?php

namespace Cpvm\Block\App\Repositories;

use Adtech\Application\Cms\Repositories\Eloquent\Repository;
use Illuminate\Support\Facades\DB;

/**
 * Class DemoRepository
 * @package Cpvm\Block\Repositories
 */
class BlockRepository extends Repository
{

    /**
     * @return string
     */
    public function model()
    {
        return 'Cpvm\Block\App\Models\Block';
    }

    public function findAll() {

        DB::statement(DB::raw('set @rownum=0'));
        $result = $this->model::query();
        $result->select('block.*', DB::raw('@rownum  := @rownum  + 1 AS rownum'));

        return $result;
    }
}
