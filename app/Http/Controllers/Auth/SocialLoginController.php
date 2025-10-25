<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class SocialLoginController extends Controller
{
    // GOOGLE
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        $googleUser = Socialite::driver('google')->stateless()->user();

        // 1. Find user by email
        $user = User::where('email', $googleUser->getEmail())->first();

        if ($user) {
            // 2. User exists: Update only Google info
            // This leaves the manual password unchanged.
            $user->update([
                'google_id' => $googleUser->getId(),
                'avatar' => $googleUser->getAvatar(),
            ]);
        } else {
            // 3. New user: Create a new account
            $user = User::create([
                'email' => $googleUser->getEmail(),
                'name' => $googleUser->getName(),
                'google_id' => $googleUser->getId(),
                'avatar' => $googleUser->getAvatar(),
                'password' => bcrypt(Str::random(16)), // Set password only for new users
            ]);
        }

        // 4. Log in the user
        Auth::login($user);

        return redirect()->route('home'); // redirect to homepage
    }

    // FACEBOOK
    public function redirectToFacebook()
    {
        return Socialite::driver('facebook')->redirect();
    }

    public function handleFacebookCallback()
    {
        $facebookUser = Socialite::driver('facebook')->stateless()->user();

        // 1. Find user by email
        $user = User::where('email', $facebookUser->getEmail())->first();

        if ($user) {
            // 2. User exists: Update only Facebook info
            $user->update([
                'facebook_id' => $facebookUser->getId(),
                'avatar' => $facebookUser->getAvatar(),
            ]);
        } else {
            // 3. New user: Create a new account
            $user = User::create([
                'email' => $facebookUser->getEmail(),
                'name' => $facebookUser->getName(),
                'facebook_id' => $facebookUser->getId(),
                'avatar' => $facebookUser->getAvatar(),
                'password' => bcrypt(Str::random(16)), // Set password only for new users
            ]);
        }

        // 4. Log in the user
        Auth::login($user);

        return redirect()->route('home'); // redirect to homepage
    }
}
