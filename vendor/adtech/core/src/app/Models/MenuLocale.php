<?php

namespace Adtech\Core\App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuLocale extends Model
{
    protected $connection = 'mysql_core';

    protected $table = 'adtech_core_menus_locale';

    public $timestamps = false;

    protected $fillable = ['name'];
}
