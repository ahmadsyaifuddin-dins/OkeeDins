<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    // use AuthenticatesUsers;

    protected $redirectTo = '/home'; // Change this to your desired redirect path

    public function showLoginForm()
    {
        return view('auth.login'); // Ensure this view exists
    }
}
