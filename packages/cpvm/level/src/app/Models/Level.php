<?php

namespace Cpvm\Level\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Level extends Model {
    use SoftDeletes;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'cpvm_level';

    protected $primaryKey = 'level_id';

    protected $fillable = ['name'];

    protected $dates = ['deleted_at'];
}
