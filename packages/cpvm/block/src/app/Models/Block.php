<?php

namespace Cpvm\Block\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Block extends Model {
    use SoftDeletes;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'block';

    protected $primaryKey = 'block_id';

    protected $fillable = ['name'];

    protected $dates = ['deleted_at'];
}
