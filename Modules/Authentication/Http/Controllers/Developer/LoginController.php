<?php

namespace Modules\Authentication\Http\Controllers\Developer;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Authentication\Foundation\Authentication;
use Modules\Authentication\Http\Requests\Dashboard\LoginRequest;

class LoginController extends Controller
{
    use Authentication;

    /**
     * Display a listing of the resource.
     */
    public function showLogin()
    {
        return view('authentication::dashboard.auth.login');
    }

    /**
     * Login method
     */
    public function postLogin(LoginRequest $request)
    {
        $errors =  $this->login($request);

        if ($errors)
            return redirect()->back()->withErrors($errors)->withInput($request->except('password'));

        return redirect()->route('dashboard.home');
    }


    /**
     * Logout method
     */
    public function logout(Request $request)
    {
        auth('developer')->logout();
        return redirect()->route('dashboard.home');
    }

}
