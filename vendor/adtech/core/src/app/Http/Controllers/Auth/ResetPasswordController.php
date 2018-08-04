<?php

namespace Adtech\Core\App\Http\Controllers\Auth;

use Adtech\Core\App\Mail\Password as PasswordMailer;
use Adtech\Application\Cms\Controllers\Controller as Controller;
use Adtech\Core\App\Repositories\PasswordResetRepository;
use Adtech\Core\App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Mail;

class ResetPasswordController extends Controller
{
    private $_passwordResetRepository;

    private $_userRepository;

    public function __construct(PasswordResetRepository $passwordResetRepository, UserRepository $userRepository)
    {
        parent::__construct();
        $this->_passwordResetRepository = $passwordResetRepository;
        $this->_userRepository = $userRepository;
    }

    /**
     * @param Request $request
     * @param String $resetToken
     */
    public function reset(Request $request, $resetToken)
    {
        $passwordReset = $this->_passwordResetRepository->findWhere(['token' => $resetToken])->sortBy('created_at', 0, true)->first();
        if (null == $passwordReset) {
            return redirect(route('adtech.core.auth.login'));
        }

        $createAtTimestamp = strtotime($passwordReset->created_at);
        if ((time() - $createAtTimestamp) > 10 * 60) {
            $this->_passwordResetRepository->delete($passwordReset->id);
            \Session::flash('flash_messenger', trans('adtech-core::messages.reset_password_failed'));
            return redirect(route('adtech.core.auth.forgot'));
        }

        if ($request->isMethod('post')) {
            $password = $request->input('inputPassword');
            $confirmPassword = $request->input('inputConfirmPassword');

            $data = [
                'password' => Hash::make($password)
            ];
            $this->_userRepository->update($data, $passwordReset->email, 'email');
            //$this->_passwordResetRepository->delete($passwordReset->id);


            $from = config('mail.from.address');
            $fromName = config('mail.from.name');

            $title = trans('adtech-core::mail.reset_password.title');

            $resetPasswordMailer = new PasswordMailer();
            $resetPasswordMailer->setViewFile('modules.core.auth.mail.reset_password')
                ->with([
                    'toName' => $passwordReset->email,
                    'email' => $passwordReset->email,
                    'loginLink' => route('adtech.core.auth.login')
                ])
                ->from($from, $fromName)
                ->subject($title);

            try {
                Mail::to($passwordReset->email, $passwordReset->email)->send($resetPasswordMailer);
                \Session::flash('flash_messenger', trans('adtech-core::messages.reset_password_success'));
                return redirect(route('adtech.core.auth.login'));
            } catch (Exception $e) {
            }
        }

        return view('modules.core.auth.forgot-password-confirm');
    }
}
