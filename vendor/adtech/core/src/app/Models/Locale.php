<?php

namespace Adtech\Core\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Locale extends Model
{
    use SoftDeletes;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $connection = 'mysql_core';

    protected $table = 'adtech_core_locales';

    protected $primaryKey = 'locale_id';

    protected $fillable = ['name', 'alias', 'icon', 'domain_id'];

    protected $dates = ['deleted_at'];
}
