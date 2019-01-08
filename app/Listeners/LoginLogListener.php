<?php

namespace App\Listeners;

use App\Events\LoginLogEvent;
use App\Models\LoginLog;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Request;

class LoginLogListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * @param LoginLogEvent $event
     */
    public function handle(LoginLogEvent $event)
    {
        $user = $event->user;
        LoginLog::create([
            'uid'=>$user->uid,
            'login_date'=>date('Y-m-d'),
            'ip' => Request::getClientIp(),
            'user_agent' => $_SERVER['HTTP_USER_AGENT']
        ]);
    }
}
