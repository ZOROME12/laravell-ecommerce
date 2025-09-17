@extends('layouts.app')

@section('contents')
<div class="max-w-md mx-auto mt-10 bg-white p-6 rounded shadow">
    <h2 class="text-xl font-bold mb-4">Two-Factor Verification</h2>
    <p class="mb-4 text-gray-600">We sent a 6-digit code to your email. Enter it below:</p>

    <form method="POST" action="{{ route('mfa.verify') }}">
        @csrf
        <input type="hidden" name="email" value="{{ $email }}">

        <div class="mb-4">
            <label class="block font-semibold">Verification Code</label>
            <input type="text" name="code" class="w-full border rounded p-2" required>
            @error('code') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
        </div>

        <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded w-full">
            Verify
        </button>
    </form>
</div>
@endsection
