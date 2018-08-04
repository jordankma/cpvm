<?php

namespace Adtech\Core\App\Http\Controllers\Auth;

use Adtech\Application\Cms\Controllers\Controller as Controller;
use Adtech\Application\Cms\Traits\CaptchaTrait;
use Adtech\Application\Cms\Traits\ActivationTrait;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Adtech\Core\App\Models\User;
use Adtech\Core\App\Models\Role;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */
    use RegistersUsers, ActivationTrait, CaptchaTrait;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $data['captcha'] = $this->captchaCheck();
        $validator = Validator::make($data, [
            'email' => 'required|email|unique:adtech_core_users',
            'password' => 'required|min:6|max:30',
            'confirmPassword' => 'required|same:password',
            'g-recaptcha-response' => 'required',
            'captcha' => 'required|min:1'
        ], [
                'email.required' => trans('adtech-core::messages.email_required'),
                'email.email' => trans('adtech-core::messages.email_invalid'),
                'password.required' => trans('adtech-core::messages.password_required'),
                'password.min' => trans('adtech-core::messages.password_min'),
                'password.max' => trans('adtech-core::messages.password_max'),
                'g-recaptcha-response.required' => trans('adtech-core::messages.captcha_required'),
                'captcha.min' => trans('adtech-core::messages.captcha_invalid')
            ]
        );
        return $validator;
    }

    public function create(Request $request)
    {
        if ($request->isMethod('post')) {
            $data = $request->all();
            $validator = $this->validator($request->all());
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator);
            } else {
                $user = User::create([
                    'email' => $data['email'],
                    'password' => Hash::make($data['password']),
                    'activated_code' => str_random(64),
                    'salt' => str_random(3),
                    'status' => -1,
                ]);
                $role = Role::whereName('User')->first();
                $user->assignRole($role);
                $this->initiateEmailActivation($user);
            }
        }
        return view('modules.core.auth.register');
    }
}
