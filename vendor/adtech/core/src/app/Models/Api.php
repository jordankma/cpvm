<?php

namespace Adtech\Core\App\Models;

use Illuminate\Database\Eloquent\Model;
use \Adtech\Application\Cms\Libraries\Acl as AdtechAcl;
use Illuminate\Database\Eloquent\SoftDeletes;

class Api extends Model
{
    use SoftDeletes;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $connection = 'mysql_core';

    protected $table = 'adtech_core_api';

    protected $primaryKey = 'api_id';

    protected $fillable = ['package_id', 'name', 'link', 'description', 'datademo', 'status'];

    protected $dates = ['deleted_at'];
}
