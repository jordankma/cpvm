<?php

namespace Adtech\Core\App\Repositories;

use Adtech\Application\Cms\Repositories\Eloquent\Repository;

class UserRepository extends Repository
{
    /**
     * @return string
     */
    public function model()
    {
        return 'Adtech\Core\App\Models\User';
    }

    public function auth($email, $password, $columns = array('*'))
    {
        $user = $this->model->where('email', '=', $email)->first($columns);
        if ($user != null && \Hash::check($password, $user->password)) {
            return $user;
        }

        return null;
    }

    public function findAll() {
        $result = $this->model::query();
        return $result;
    }

    public function getById($id, $columns = ['*'])
    {
        return $this->model->where('user_id', '=', $id)->first($columns);
    }

    public function getByUsername($username, $columns = ['*'])
    {
        return $this->model->where('username', '=', $username)->first($columns);
    }

    public function countAll($keyword, $matchThese, $limit, $order, $sort, $role)
    {
        $result = $this->model->whereHas('roles', function ($query) use ($role) {
            if ($role > 0)
                $query->where('adtech_core_users_role.role_id', $role);
        })
            ->where('status', '!=', -1)
            ->where(function ($query) use ($keyword) {
                $query->orWhere('email', 'like', '%' . $keyword . '%')
                    ->orWhere('username', 'like', '%' . $keyword . '%')
                    ->orWhere('contact_name', 'like', '%' . $keyword . '%');
            })->where($matchThese)->count();
        return $result;
    }

    public function findAllByPaginate($attribute, $value, $limit)
    {
        $result = $this->model->where($attribute, $value)->paginate($limit);
        return $result;
    }

    public function findAllInfoByPaginate($limit)
    {
        $result = $this->model->where('status', '!=', -1)->paginate($limit);
        return $result;
    }

    public function findAllByFilter($keyword, $matchThese, $limit, $order, $sort, $role)
    {
        $result = $this->model->whereHas('roles', function ($query) use ($role) {
            if ($role > 0)
                $query->where('adtech_core_users_role.role_id', $role);
        })
            ->where('status', '!=', -1)
            ->where(function ($query) use ($keyword) {
                $query->orWhere('email', 'like', '%' . $keyword . '%')
                    ->orWhere('username', 'like', '%' . $keyword . '%')
                    ->orWhere('contact_name', 'like', '%' . $keyword . '%');
            })->where($matchThese)->orderBy($order, $sort)->paginate($limit);
        return $result;
    }

    public function findID($user_id)
    {
        $user = $this->model->find($user_id);
        $user->id = $user_id;
        $user->statusLB = ($user->status == 1) ? true : false;
        $user->activatedLB = ($user->activated == 1) ? true : false;
        $user->activatedLBB = ($user->activated == 1) ? "Activated" : "Wait";
        $user->permission_lockedLB = ($user->permission_locked == 1) ? true : false;
        $user->role_id = isset($user->roles[0]) ? $user->roles[0]->role_id : null;
        $user->role_name = isset($user->roles[0]) ? $user->roles[0]->name : null;
        $user->url_permission_details = route('adtech.core.permission.details', [
            'object_type' => 'user',
            'object_id' => $user->user_id,
        ]);
        $user->success = true;
        return $user;
    }
}
