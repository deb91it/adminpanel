<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use Session;

class SessionTimeout
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
        /*if(Auth::check()) {
            $max_time_out = config('session.time_out');
            $last_active = strtotime(\Session::get('last_active'));
            $now = strtotime(date('Y-m-d H:i:s'));
            $diff = round(abs($now - $last_active) / 60,2);
            if ($max_time_out < $diff) {
                if (get_logged_user_type() == '0') {
                    return redirect('admin/lock');
                }
                return redirect('lock');
            }
        }*/

        return $next($request);
    }
}
