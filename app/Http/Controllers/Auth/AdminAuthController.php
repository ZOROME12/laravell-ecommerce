<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin;
use Illuminate\Support\Facades\Log;

class AdminAuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        Log::info('Login attempt', $credentials);

        if (Auth::guard('admin')->attempt($credentials)) {
            $admin = Auth::guard('admin')->user();
            $token = $admin->createToken('admin-token', ['admin'])->plainTextToken;
            Log::info('Login successful', ['token' => $token, 'admin' => $admin->email]);
            return response()->json([
                'token' => $token,
                'admin' => $admin,
            ]);
        }

        Log::warning('Login failed', ['email' => $credentials['email'], 'reason' => 'Invalid credentials']);
        return response()->json(['message' => 'Invalid email or password. Please try again.'], 401);
    }
}
