<?php

namespace Adtech\Core\App\Models;

use Illuminate\Database\Eloquent\Model;
use \Adtech\Application\Cms\Libraries\Acl as AdtechAcl;
use Illuminate\Database\Eloquent\SoftDeletes;

class Domain extends Model
{
    use SoftDeletes;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $connection = 'mysql_core';

    protected $table = 'adtech_core_domains';

    protected $primaryKey = 'domain_id';

    protected $fillable = ['name'];

    protected $dates = ['deleted_at'];

    public function hasPackage($package, $module)
    {
        foreach ($this->packages as $packageItem) {
            if ($packageItem->package == $package && $packageItem->module == $module) return true;
        }
        return false;
    }

    public function assignPackage($package)
    {
        return $this->packages()->attach($package);
    }

    public function removePackage($package)
    {
        return $this->packages()->detach($package);
    }
}
