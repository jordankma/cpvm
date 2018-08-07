<?php

namespace Cpvm\Block\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BlockHasSubject extends Model {
    use SoftDeletes;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'block_has_subject';

    protected $primaryKey = 'block_has_subject_id';

    protected $dates = ['deleted_at'];
}
