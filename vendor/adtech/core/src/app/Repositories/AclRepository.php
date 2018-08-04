<?php

namespace Adtech\Core\App\Repositories;

use Adtech\Application\Cms\Repositories\Eloquent\Repository;
use Illuminate\Support\Facades\DB;

class AclRepository extends Repository
{
    /**
     * @return string
     */
    public function model()
    {
        return 'Adtech\Core\App\Models\Acl';
    }

    public function getAllRules($columns = array('*'))
    {
        $columns = [
            DB::raw('CONCAT(`object_type`, \'_\', `object_id`) as `role_name`'),
            'allow', 'route_name'
        ];
        /*
         'SELECT CONCAT(ru.`object_type`, \'_\', ru.`object_id`) AS `role_name`, ru.`allow`, r.`route_key`
        FROM `' . $prefix . $this->_table . '` AS ru
        LEFT JOIN `' . $prefix . 'core_route` AS r
            ON ru.`route_key_crc` = r.`route_key_crc`
        WHERE ru.`route_key_crc` IS NULL

        UNION

        SELECT CONCAT(ru.`object_type`, \'_\', ru.`object_id`) AS `role_name`, ru.`allow`, r.`route_key`
        FROM `' . $prefix . $this->_table . '` AS ru
        INNER JOIN `' . $prefix . 'core_route` AS r
            ON ru.`route_key_crc` = r.`route_key_crc`';
         */

        return $this->model->select($columns)
            ->where('route_name_crc', '=', '0')
            ->union($this->model->select($columns)
                ->where('route_name_crc', '<>', '0'))
            ->get();
    }
}