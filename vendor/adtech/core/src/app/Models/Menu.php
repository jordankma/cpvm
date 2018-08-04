<?php

namespace Adtech\Core\App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Dimsav\Translatable\Translatable;

class Menu extends Model
{
    use SoftDeletes, Translatable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $connection = 'mysql_core';

    protected $table = 'adtech_core_menus';

    protected $primaryKey = 'menu_id';

    public $translatedAttributes = ['name'];

    public $translationModel = 'Adtech\Core\App\Models\MenuLocale';

    protected $fillable = ['type', 'parent', 'route_name', 'domain_id', 'group', 'typeData', 'typeView'];

    protected $dates = ['deleted_at'];

    public function domains()
    {
        return $this->hasMany('Adtech\Core\App\Models\Domain');
    }
}
