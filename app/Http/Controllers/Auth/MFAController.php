<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Carbon\Carbon;

class MFAController extends Controller
{
    public function showForm(Request $request)
    {
        $email = session('mfa_email');

        if (!$email) {
            return redirect()->route('login')->withErrors([
                'email' => 'Session expired, please login again.'
            ]);
        }

        return view('auth.mfa-verify', compact('email'));
    }

    public function verify(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'code'  => 'required|numeric',
        ]);

        $user = User::where('email', $request->email)->first();

        if (
            !$user ||
            $user->mfa_code !== $request->code ||
            Carbon::now()->gt($user->mfa_expires_at)
        ) {
            return back()->withErrors(['code' => 'Invalid or expired verification code.']);
        }

        // Reset MFA fields
        $user->mfa_code = null;
        $user->mfa_expires_at = null;
        $user->save();

        // Login the user (final authentication)
        Auth::login($user);

        // Clear session
        session()->forget('mfa_email');

        return redirect()->intended('/dashboard')->with('success', 'You are now logged in with MFA!');
    }
}
