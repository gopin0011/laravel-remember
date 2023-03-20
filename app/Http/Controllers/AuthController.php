<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
// use Cookie;

class AuthController extends Controller
{
    public $remember = null;

    public function attemptLogin()
    {
        $remember = \Cookie::get(Auth::getRecallerName());
        if(Auth::check() === false && $remember !== null) {
            $credentials = explode("|", $remember);
            $user = User::find($credentials[0]);
            if($user->password == $credentials[2])
            {
                Auth::login($user);
                return true;
            }
            return false;
        }
        if (Auth::check()) {
            return true;
        }

        return false;
    }

    public function show()
    {
        // dd(\Cookie::get(Auth::getRecallerName()));
        if(!$this->attemptLogin()) {
            return view('auth.login');
        }

        return redirect()->intended();
    }

    /**
     * Handle account login request
     * 
     * @param LoginRequest $request
     * 
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        // $credentials = $request->getCredentials();
        // if(!Auth::validate($credentials)) {
        //     return redirect()->to('login')
        //         ->withErrors(trans('auth.failed'));
        // }

        // $user = Auth::getProvider()->retrieveByCredentials($credentials);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials, ($request->get('remember') ?? false))) {
            return redirect()->intended();
        }

        return [
            "code"      => 401,
            "message"   => "Email and password didn't match",
        ];

        $user = User::where("email", $request->email)->first();
        if (!empty($user)) {
            if(! Hash::check($request->password, $user->password)) {
                return [
                    "code"      => 401,
                    "message"   => "Email and password didn't match",
                ];
            }

            Auth::login($user, $request->get('remember'));

            return $this->authenticated($request, $user);
        }

        return redirect()->to('login')
            ->withErrors(trans('auth.failed'));
    }

    public function logout()
    {
        Auth::logout();
    }

    /**
     * Handle response after user authenticated
     * 
     * @param Request $request
     * @param Auth $user
     * 
     * @return \Illuminate\Http\Response
     */
    protected function authenticated(Request $request, $user) 
    {
        return redirect()->intended();
    }
}
