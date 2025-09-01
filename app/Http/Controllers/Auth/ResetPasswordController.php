<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;

class ResetPasswordController extends Controller
{
    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Get the post-password-reset redirect path based on user role.
     *
     * @return string
     */
    public function redirectTo()
    {
        if (auth()->user()->hasRole('Admin')) {
            return '/home';
        }

        return '/';
    }
}
