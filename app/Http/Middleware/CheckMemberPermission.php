<?php

namespace App\Http\Middleware;

use Closure;
use App\Services\MemberAccess;

class CheckMemberPermission
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
        $access = new MemberAccess();
        $client = $request->header('user-agent');
        $access_token = $request->header('Authorization');
        if ($access_token == '') {
            $this->response['success'] = false;
            $this->response['message'] = 'Access token is required to complete this operation';
            $this->response['error'] = get_error_response(420, "You didn't sent token with request");
            return response($this->response, 420);
        }

        $member_id = $access->check_member_token_validity($access_token, $client);
        if ($member_id != null) {
            if ($access->can($permission, $member_id)) {
                return $next($request);
            } else {
                $this->response['success'] = false;
                $this->response['message'] = 'You do not have the permission to access';
                $this->response['error'] = get_error_response(403, "You do not have the permission to access");
                return response($this->response, 403);
            }
        }

        $this->response['success'] = false;
        $this->response['token_error_code'] = 420;
        $this->response['message'] = 'Invalid token';
        $this->response['error'] = get_error_response(420, "Your provided token is not valid");
        return response($this->response, 420);
    }
}
