<?php

namespace Adtech\Core\App\Http\Controllers\Auth;

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

    /**
     * @param Request $request
     * @return mixed
     */
    private function _authenticate(Request $request)
    {
        $routePrefix = $request->route()->getPrefix();
        $email = $request->input('email');
        $password = $request->input('password');
        $remember = $request->input('remember', false);
        if ($this->_guard()->attempt(['email' => $email, 'password' => $password], $remember)) {
            $request->session()->regenerateToken();
            \Session::flash('flash_messenger', trans('adtech-core::messages.login_success'));
            $routeName = $routePrefix == config('site.admin_prefix') ? 'backend.homepage' : 'frontend.homepage';
            shell_exec('cd ../ && /egserver/php/bin/php artisan view:clear');
            return redirect()->intended(route($routeName));
        } else {
            $request->session()->regenerateToken();
            \Session::flash('flash_messenger', trans('adtech-core::messages.login_failed'));
            return redirect()->back()
                ->withInput($request->only('email', 'remember'))
                ->withErrors([
                    'inputEmail' => trans('adtech-core::messages.login_failed'),
                    'inputPassword' => trans('adtech-core::messages.login_failed')
                ]);
        }

        return null;
    }

    public function login(Request $request)
    {
        if ($this->user) {
            $routeName = 'backend.homepage';
            return redirect()->intended(route($routeName));
        }

        if ($request->isMethod('post')) {
            return $this->_authenticate($request);
        }

        return view('modules.core.auth.login');
    }

    public function logout(Request $request)
    {
        $this->_guard()->logout();

        $request->session()->flush();

        //$request->session()->regenerate();

        \Session::flash('flash_messenger', trans('adtech-core::messages.logout_success'));

        return redirect(route('adtech.core.auth.login'));
    }
}
