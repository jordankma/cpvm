<?php

namespace Adtech\Core\App\Listeners;

use Illuminate\Support\Facades\Session;

use Adtech\Application\Cms\Libraries\Firebase\Message as FirebaseMessage;

class UserEventSubscriber
{
    /**
     * Handle user login events.
     */
    public function onUserLogin($event)
    {
        $user = $event->user;
        /**
         * Firebase notification
         */
        $sessionId = Session::getId();

        $data = [
            'collapse_key' => 'USER_LOGIN_NOTIFICATION',
            'priority' => 'high',
            'title' => config('app.name'),
            'body' => trans('adtech-core::messages.sign_in_at', [
                'EMAIL' => $user->email,
                'TIME' => date('H:i:s, d/m/Y'),
                'SESSION_ID' => substr($sessionId, -10),
            ]),
        ];
        return FirebaseMessage::getInstance()->send($data);
    }

    /**
     * Handle user logout events.
     */
    public function onUserLogout($event)
    {
        $user = $event->user;
        /**
         * Firebase notification
         */
        $sessionId = Session::getId();

        $data = [
            'collapse_key' => 'USER_LOGIN_NOTIFICATION',
            'priority' => 'high',
            'title' => config('app.name'),
            'body' => trans('adtech-core::messages.sign_out_at', [
                'EMAIL' => $user->email,
                'TIME' => date('H:i:s, d/m/Y'),
                'SESSION_ID' => substr($sessionId, -10),
            ]),
        ];
        return FirebaseMessage::getInstance()->send($data);
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param  \Illuminate\Events\Dispatcher $events
     */
    public function subscribe($events)
    {
        $events->listen(
            'Illuminate\Auth\Events\Login',
            'Adtech\Core\App\Listeners\UserEventSubscriber@onUserLogin'
        );

        $events->listen(
            'Illuminate\Auth\Events\Logout',
            'Adtech\Core\App\Listeners\UserEventSubscriber@onUserLogout'
        );
    }

}