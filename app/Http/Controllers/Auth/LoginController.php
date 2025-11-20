<?php

namespace App\Http\Controllers\Auth;

use App\Models\MUser;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function username()
    {
        return 'username';
    }

    public function login(Request $request)
    {
        $this->validateLogin($request);

        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        $user = MUser::where('Username', $request->username)
            ->where('IsDel', 0)
            ->first();

        if ($user) {
            if (trim($user->Passwd) == trim($request->password)) {
                return $this->authenticate($request, $user);
            }
        }


        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

    private function authenticate(Request $request, MUser $user)
    {
        auth()->login($user);

        return $this->sendLoginResponse($request);
    }
}
