<?php

namespace App\Http\Controllers\Admin;

use App\DB\Admin\Agent;
use App\DB\Member;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\Guard;
use App\User;
use App\DB\Admin\AdminUser;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\Admin\Auth\LoginRequest;
use Illuminate\Support\Facades\Hash;
use Session;
class AdminAuthController extends Controller
{
    /**
     * the model instance
     * @var User
     */
    protected $user;
    /**
     * The Guard implementation.
     *
     * @var Authenticator
     */
    protected $auth;

    /**
     * Create a new authentication controller instance.
     *
     * @param  Authenticator  $auth
     * @return void
     */

    public function __construct(Guard $auth, User $user)
    {
        $this->user = $user;
        $this->auth = $auth;

       // $this->middleware('guest', ['except' => ['getLogout']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
      public function getLogin()
      {
          //return view('admin.auth.login');
          if (! $this->auth->check()) return view('admin/auth/login');
          else return redirect('admin');
      }

      /**
       * Show the form for creating a new resource.
       *
       * @return Response
       */
    public function postLogin(LoginRequest $request)
    {
        $email = $request->input('email');
        $password = $request->input('password');

        $user = Member::where(['email' => $email])->where(function ($query) {
            $query->where('user_type', 0);
               // ->orWhere('user_type', 4);
        })->first();

        if ($this->hasExist($user)) {
            if ($this->auth->attempt(['email' => $email, 'password' => $password, 'user_type' => $user->user_type], $request->has('remember')))
            {
                session([
                    'email'         => $email,
                    'last_active'   => date('Y-m-d H:i:s'),
                    'user_type'     => $user->user_type,
                    'has_company'   => 0
                ]);
                if ($user->user_type == '0') {
                    $admin_user = AdminUser::where('member_id', get_logged_user_id())->first();
                    session(['profile_pic' => $admin_user->profile_pic]);
                    return redirect('/admin');

                } else if ($user->user_type == '4') {
                    $agent_user = Agent::where('member_id', get_logged_user_id())->first();
                    session(['profile_pic' => $agent_user->profile_pic]);
                    session(['has_company' => $agent_user->has_company]);
                    return redirect('dashboard');

                }
            }
        }

        return redirect()->back()->withErrors([
            'email' => 'The credentials you entered did not match our records. Try again?',
        ]);

      /*  return redirect('/admin/login')->withErrors([
            'email' => 'The credentials you entered did not match our records. Try again?',
        ]);*/
    }

    private function hasExist($user_array){
        if (! empty($user_array )) return true;
        return false;
    }

    public function getLogout()
    {
        $user_type = get_logged_user_type();
        $this->auth->logout();
        if ($user_type == '0') {
            return redirect('/');
        }
        return redirect('/');
    }

    public function getAgentLockScreen()
    {
        if (! $this->auth->check()) return redirect('/login');
        else if (Session::has('email')) return view('agent/auth/lock-screen')->withUser([
            'email' => Session::get('email'),
            'profile_pic' => Session::get('profile_pic'),
            'name' => get_logged_user_name(),
        ]);
        else return redirect('/');
    }

    public function getLockScreen()
    {
        if (! $this->auth->check()) return redirect('/admin/login');
        else if (Session::has('email')) return view('admin/auth/lock-screen')->withUser([
            'email' => Session::get('email'),
            'profile_pic' => Session::get('profile_pic'),
            'name' => get_logged_user_name(),
        ]);
        else return redirect('admin');
    }

    public function postLockScreen(LoginRequest $request)
    {
        $email = $request->input('email');
        if ($email == '') return redirect('/login');
        $password = $request->input('password');

        $user = Member::where(['email' => $email])->where(function ($query) {
            $query->where('user_type', 0)
                ->orWhere('user_type', 4);
        })->first();

        if ($this->hasExist($user)) {
            if ($this->auth->attempt(['email' => $email, 'password' => $password, 'user_type' => $user->user_type], $request->has('remember'))) {
                session([
                    'email'         => $email,
                    'last_active'   => date('Y-m-d H:i:s'),
                    'user_type'     => $user->user_type,
                    'has_company'   => 0
                ]);

                if ($user->user_type == '0') {
                    $admin_user = AdminUser::where('member_id', get_logged_user_id())->first();
                    session(['profile_pic' => $admin_user->profile_pic]);
                    return redirect('/admin');

                } else if ($user->user_type == '4') {
                    $agent_user = Agent::where('member_id', get_logged_user_id())->first();
                    session(['profile_pic' => $agent_user->profile_pic]);
                    session(['has_company' => $agent_user->has_company]);
                    return redirect('/');
                }
            }
        }

        return redirect()->back()->withErrors([
            'email' => 'The credentials you entered did not match our records. Try again?',
        ]);
    }

    public function privacyPolicy() {
      return view('privacy-policy.index');
    }
}
