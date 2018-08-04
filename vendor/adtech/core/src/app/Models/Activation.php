<?php

namespace Adtech\Core\App\Models;

use Illuminate\Database\Eloquent\Model;

class Activation extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $connection = 'mysql_core';

    protected $table = 'adtech_core_users_activation';

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
