<?php

namespace Adtech\Core\App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use \Adtech\Application\Cms\Libraries\Acl as AdtechAcl;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract
{
    use Authenticatable, CanResetPassword, Notifiable, SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $connection = 'mysql_core';

    protected $table = 'adtech_core_users';

    protected $primaryKey = 'user_id';

    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = ['user_id'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token', 'salt'];

    protected $dates = ['deleted_at'];

    private $rules = array(
        'email' => 'required|email|unique:mysql_core.adtech_core_users',
        'password' => 'required|min:6|confirmed',
        'password_confirmation' => 'required|min:6',
        'contact_name' => 'required',
        'status' => 'boolean',
        'activated' => 'boolean',
        'permission_locked' => 'boolean'
    );

    public function validate($data)
    {
        $validator = Validator::make($data, $this->rules, $this->messages);
        return $validator;
    }

    public function roles()
    {
        return $this->belongsToMany('Adtech\Core\App\Models\Role', 'adtech_core_users_role', 'user_id', 'role_id')->withTimestamps();
    }

    public function hasRole($name)
    {
        foreach ($this->roles as $role) {
            if ($role->name == $name) return true;
        }
        return false;
    }

    public function assignRole($role)
    {
        return $this->roles()->attach($role);
    }

    public function removeRole($role)
    {
        return $this->roles()->detach($role);
    }

    public function canAccess($routeName, $params = null)
    {
        return AdtechAcl::getInstance()->isAllow($routeName, $params, $this);
    }
}
