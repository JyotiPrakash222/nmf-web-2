<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class LoginController extends Controller
{
    

    public function showLoginForm()
    {
        $remember = false;

        if (Auth::viaRemember()) {
            $remember = true;
        }

        return view('auth.login', compact('remember'));
    }


    // Handle login logic
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        $remember = $request->has('remember');

        $user = User::where('email', $credentials['email'])
            ->where('status', 1)
            ->first();

        if ($user) {
            if (Auth::attempt($credentials, $remember)) {
                return redirect()->intended('/home');
            }

            return back()->withErrors([
                'email' => 'Invalid credentials.',
            ])->withInput();
        }

        return back()->withErrors([
            'email' => 'Your account is inactive or does not exist.',
        ])->withInput();
    }


    // Logout user
    public function logout(Request $request)
    {
        Auth::logout();
        return redirect('/');
    }
}
