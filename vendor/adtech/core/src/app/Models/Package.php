<?php

namespace Adtech\Core\App\Models;

use Illuminate\Database\Eloquent\Model;
use \Adtech\Application\Cms\Libraries\Acl as AdtechAcl;
use Illuminate\Database\Eloquent\SoftDeletes;

class Package extends Model
{
    use SoftDeletes;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $connection = 'mysql_core';

    protected $table = 'adtech_core_packages';

    protected $primaryKey = 'package_id';

    protected $fillable = ['package', 'package_alias', 'module', 'module_alias', 'space'];

    protected $dates = ['deleted_at'];

    public function domains()
    {
        return $this->belongsToMany('Adtech\Core\App\Models\Domain', 'adtech_core_domains_package', 'package_id', 'domain_id')->withPivot(['status', 'created_at', 'updated_at', 'deleted_at']);
    }

    public function api()
    {
        return $this->hasMany('Adtech\Core\App\Models\Api', 'package_id', 'package_id');
    }
}
