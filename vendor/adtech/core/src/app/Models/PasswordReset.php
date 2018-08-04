<?php

namespace Adtech\Core\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PasswordReset extends Model
{
    use SoftDeletes;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $connection = 'mysql_core';

    protected $table = 'adtech_password_resets';

    protected $fillable = ['email', 'token', 'created_at'];

    protected $dates = ['deleted_at'];
}
