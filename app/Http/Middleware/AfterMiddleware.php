<?php

namespace App\Http\Middleware;

use Closure;
use App\DB\Admin\AdminUser;
use App\DB\Admin\Agent;

class AfterMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = '';
        if (get_logged_user_type() == '0') {
            $user = AdminUser::where('member_id', get_logged_user_id())->first();
        } else if (get_logged_user_type() == '4') {
            $user = Agent::where('member_id', get_logged_user_id())->first();
        }

        if (!empty($user)) {
            session(['profile_pic' => $user->profile_pic]);
        }

        return $next($request);
    }
}