<?php

namespace Adtech\Core\App\Repositories;

use Adtech\Application\Cms\Repositories\Eloquent\Repository;

class PasswordResetRepository extends Repository
{
    /**
     * @return string
     */
    public function model()
    {
        return 'Adtech\Core\App\Models\PasswordReset';
    }

    public function auth($email, $password, $columns = array('*'))
    {
        $user = $this->model->where('email', '=', $email)->first($columns);
        if ($user != null && \Hash::check($password, $user->password)) {
            return $user;
        }

        return null;
    }
}