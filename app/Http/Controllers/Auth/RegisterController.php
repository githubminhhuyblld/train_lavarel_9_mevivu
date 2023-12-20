<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Manager\Auth\AuthManager;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;


class RegisterController extends Controller
{
    private AuthManager $authManager;

    public function __construct(AuthManager $authManager)
    {
        $this->authManager = $authManager;
    }

    public function show(): View
    {
        return view('auth.register.register');
    }

    public function register(RegisterRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $user = [
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ];
        $this->authManager->create($user);

        return redirect()->route('login.show');
    }
}
