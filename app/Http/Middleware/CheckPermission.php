<?php

namespace App\Http\Middleware;

use Closure;
use App\Services\Access;
use Redirect;
use Session;

class CheckPermission
{

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */

    public function handle($request, Closure $next, $permission = null)
    {
        // Set last activity
        Session::put('last_active', date('Y-m-d H:i:s'));

        $access = new Access();
        if ($access->can($permission)) {
             return $next($request);
        }
        return redirect()->back()->with('flashMessageAlert','You do not have the permission to access');
    }
}
