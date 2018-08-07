<?php

namespace Cpvm\Block\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BlockHasClass extends Model {
    use SoftDeletes;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'block_has_class';

    protected $primaryKey = 'block_has_class_id';

    protected $dates = ['deleted_at'];
}
