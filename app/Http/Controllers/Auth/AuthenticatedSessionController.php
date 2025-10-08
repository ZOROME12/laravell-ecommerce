<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;
use Carbon\Carbon;
use App\Models\User;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $user = Auth::user();

        // Generate OTP
        $otp = rand(100000, 999999);

        // Save OTP with expiration
        $user->mfa_code = $otp;
        $user->mfa_expires_at = Carbon::now()->addMinutes(5);
        $user->save();

        // Send OTP to user's email
        Mail::raw("Your MFA verification code is: $otp", function ($message) use ($user) {
            $message->to($user->email)
                ->subject('MFA Verification Code');
        });

        // Put MFA flag in session
        session(['mfa_email' => $user->email]);

        // Redirect to MFA verification page
        return redirect()->route('mfa.form');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Redirect to login page after logout
        return redirect('/login');
    }

    /**
     * After login hook to update last login time.
     */
    protected function authenticated(Request $request, $user)
    {
        $user->update(['last_login_at' => now()]);
    }
}
