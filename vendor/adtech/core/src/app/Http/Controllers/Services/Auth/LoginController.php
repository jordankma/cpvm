<?php

namespace Adtech\Core\App\Http\Controllers\Services\Auth;

use Adtech\Application\Cms\Controllers\Controller as Controller;
use Illuminate\Http\Request;
use Auth;

class LoginController extends Controller
{
    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    private function _guard()
    {
        return Auth::guard('web');
    }

    public function check()
    {
        $user = $this->user;

        if ($user == null) {
            $response = [
                'role' => 'guest',
            ];
        } else {
            $response = [
                'id' => $user->id,
                'name' => $user->email,
                'role' => 'admin',
            ];
        }
        return response()->json($response);
    }

    public function login(Request $request)
    {
        $response = [
            'status' => 'RESULT_NOT_OK'
        ];
        $email = $request->input('email');
        $password = $request->input('password');
        $remember = $request->input('remember', false);echo $email;die;
        if ($this->_guard()->attempt(['email' => $email, 'password' => $password], $remember)) {
            $request->session()->regenerateToken();
            $response['status'] = 'RESULT_OK';
            $response['message'] = trans('adtech-core::messages.login_success');
            $response['data'] = [
                'user' => [
                    'id' => 1,
                    'email' => $email,
                    'role' => 'admin',
                ],
                'redirect' => route('frontend.homepage'),
            ];
        }

        return response()->json($response);
    }

    public function logout(Request $request)
    {
        $response = [
            'status' => 'RESULT_OK'
        ];
        $this->_guard()->logout();

        $request->session()->flush();

        $response['message'] = trans('adtech-core::messages.logout_success');

        return response()->json($response);
    }
}