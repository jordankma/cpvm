<?php

namespace Adtech\Core\App\Http\Controllers;

use Adtech\Application\Cms\Controllers\Controller as Controller;
use Illuminate\Http\Request;
use Adtech\Application\Cms\Traits\ActivationTrait;
use Adtech\Core\App\Models\Activation;
use Adtech\Core\App\Models\User;
use Auth;

class ActivateController extends Controller
{
    use ActivationTrait;

    public function activate(Request $request, $token)
    {
        $route = 'frontend.homepage';
        if ($request->route()->getPrefix() == 'site.admin_prefex') {
            $route = 'backend.homepage';
        }

        $user = $this->user;
        if ($user && $user->activated == true) {
            return redirect()->route('frontend.homepage')
                ->with('status', 'success')
                ->with('message', trans('adtech-core::messages.email_activated'));
        }

        $activation = Activation::where('token', $token)
            ->where('user_id', $this->user->id)
            ->first();

        if (empty($activation)) {

            return redirect()->route($route)
                ->with('status', 'wrong')
                ->with('message', trans('adtech-core::messages.email_activated_token_wrong'));

        }

        $user = User::where('id', $activation->user_id)->first();
        $user->activated = true;
        $user->save();

        $activation->delete();

        return redirect()->route($route)
            ->with('status', 'success')
            ->with('message', trans('adtech-core::messages.email_activated_success'));

    }

    public function resend(Request $request)
    {
        $route = 'frontend.homepage';
        if ($request->route()->getPrefix() == 'site.admin_prefix') {
            $route = 'backend.homepage';
        }

        if ($this->user && $this->user->activated == false) {
            $this->initiateEmailActivation($this->user);

            return redirect()->route($route)
                ->with('status', 'success')
                ->with('message', trans('adtech-core::messages.email_activation_sent'));
        }

        return redirect()->route($route)
            ->with('status', 'success')
            ->with('message', trans('adtech-core::messages.email_activated'));
    }
}