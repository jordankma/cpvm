<?php

namespace Cpvm\Member\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Demo extends Model {
    use SoftDeletes;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'tbl_demo';

    protected $primaryKey = 'demo_id';

    protected $fillable = ['name'];

    protected $dates = ['deleted_at'];
}
