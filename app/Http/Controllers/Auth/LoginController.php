<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Override;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = '/';

    /**
     * Validates the input for login
     *
     * @param  Request $request
     * @return null
     */
    public function validateLogin(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string']
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  Request $request
     * @return array
     */
    public function credentials(Request $request)
    {
        $email = strtolower(trim($request->input('email')));
        $password = $request->input('password');

        return ['email' => $email, 'password' => $password];
    }
}
