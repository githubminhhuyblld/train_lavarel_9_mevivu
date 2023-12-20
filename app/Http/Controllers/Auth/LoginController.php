<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;

use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LoginController extends Controller
{

    public function show(): View
    {
        return view('auth.login.login');
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            return redirect()->route('dashboard');
        } else {
            return redirect()->route('login.show')->withErrors(['login' => 'login failed !']);
        }
    }


}
