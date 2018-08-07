<?php

namespace Cpvm\Subject\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClassHasSubject extends Model {
    use SoftDeletes;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'class_has_subject';

    protected $primaryKey = 'class_has_subject_id';

    // protected $fillable = ['name'];

    protected $dates = ['deleted_at'];
}
