<?php

// app/Http/Controllers/Auth/ViewerSocialController.php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Viewer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class ViewerSocialController extends Controller
{
    // Redirect to Google
    // When user clicks "Login with Google" button:
public function redirectToGoogle()
{
    // Save the current (blog) page as intended URL
    session()->put('url.intended', url()->previous());
    return Socialite::driver('google')->redirect();
}


    // Handle Google callback
    public function handleGoogleCallback()
{
    $googleUser = Socialite::driver('google')->stateless()->user();

    $viewer = Viewer::firstOrCreate(
        ['email' => $googleUser->getEmail()],
        [
            'name'     => $googleUser->getName() ?? $googleUser->getNickname(),
            'password' => bcrypt(Str::random(16)),
        ]
    );

    Auth::guard('viewer')->login($viewer, true);

    // Redirect to the intended URL if set, otherwise use a default route
    return redirect()->intended(url('/'));
}

public function logout()
{
    Auth::guard('viewer')->logout();
    session()->invalidate();
    session()->regenerateToken();
    return redirect('/')->with('message', 'Successfully logged out!');
}


}
