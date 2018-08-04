<?php

namespace Adtech\Core\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;

class Acl extends Model
{
    use SoftDeletes;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $connection = 'mysql_core';

    protected $table = 'adtech_core_acl';

    protected $primaryKey = 'acl_id';

    protected $dates = ['deleted_at'];

    private static $_aclKey = 'ADTECH_CMS_ACL_RULES';

    public static function getAllRules()
    {
        $cache = Cache::store('file');
//        $cache->forget(self::$_aclKey);
        $data = $cache->get(self::$_aclKey);
        if (null == $data) {
            $columns = [
                DB::raw('CONCAT(`object_type`, \'_\', `object_id`) as `role_name`'),
                'allow', 'route_name', 'domain_id'
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

            $data = self::select($columns)
                ->where('route_name_crc', '=', '0')
                ->union(self::select($columns)
                    ->where('route_name_crc', '<>', '0'))
                ->get();
            $data && $cache->forever(self::$_aclKey, $data);
        }

        $return = [];
        if ($data) {
            foreach ($data as $k => $v) {
                $return[] = (object)[
                    'role_name' => $v->role_name,
                    'allow' => $v->allow,
                    'route_name' => $v->route_name,
                    'domain_id' => $v->domain_id
                ];
            }
            $cache->forever(self::$_aclKey, $return);
        }

        return $return;
    }

}
