<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class Authenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->guest()) {
            if ($request->ajax() || $request->wantsJson()) {
                $this->response['success'] = false;
                $this->response['message'] = 'WHAT UP FAHIM';
                $this->response['error'] = get_error_response(401, "HERE I AM");
                return response($this->response, 401);
            } else {
                //return redirect()->guest('login');
                return redirect()->guest('admin');
            }
        }

        if (get_logged_user_type() != '0') {
            return redirect()->back();
        }

        return $next($request);
    }
}
