<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

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

    /**
     * Runs after user is authenticated after login
     * @param Request $request
     * @param mixed $user
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function authenticated(Request $request, $user)
    {
        $profileId = UserProfile::where('user_id', $user->id)->firstOrFail()->id;
        session(['user_profile_id' => $profileId]);
        return redirect()->intended($this->redirectPath());
    }
}
