<?php

namespace Cpvm\Classes\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Classes extends Model {
    use SoftDeletes;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'cpvm_classes';

    protected $primaryKey = 'classes_id';

    protected $fillable = ['name'];

    protected $dates = ['deleted_at'];
}
