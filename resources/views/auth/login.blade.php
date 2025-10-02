<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4 text-sm text-green-600" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-sm text-[#3F1A2B] font-semibold mb-1">Email</label>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-[#B2183A]">
                    <i class="fas fa-envelope"></i>
                </span>
                <input 
                    id="email"
                    type="email" 
                    name="email" 
                    value="{{ old('email') }}"
                    required 
                    autofocus 
                    autocomplete="username"
                    class="w-full border rounded-lg pl-10 pr-3 py-2 text-sm bg-[#FBF8FB] border-gray-300 focus:ring-2 focus:ring-[#ED4A69] focus:border-[#B2183A]"
                    placeholder="Enter your email"
                >
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-[#ED4A69]" />
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-sm text-[#3F1A2B] font-semibold mb-1">Password</label>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-[#B2183A]">
                    <i class="fas fa-lock"></i>
                </span>
                <input 
                    id="password"
                    type="password" 
                    name="password" 
                    required 
                    autocomplete="current-password"
                    class="w-full border rounded-lg pl-10 pr-3 py-2 text-sm bg-[#FBF8FB] border-gray-300 focus:ring-2 focus:ring-[#ED4A69] focus:border-[#B2183A]"
                    placeholder="Enter your password"
                >
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-[#ED4A69]" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center">
            <input id="remember_me" type="checkbox"
                class="rounded border-gray-300 text-[#B2183A] shadow-sm focus:ring-[#ED4A69]" name="remember">
            <label for="remember_me" class="ml-2 text-sm text-[#3F1A2B]">
                {{ __('Remember me') }}
            </label>
        </div>

        <!-- Actions -->
        <div class="flex items-center justify-between mt-6">
            @if (Route::has('password.request'))
                <a class="text-sm font-medium text-[#B2183A] hover:text-[#ED4A69] transition"
                   href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif

            <x-primary-button
                class="px-6 py-2 bg-gradient-to-r from-[#3F1A2B] to-[#B2183A] text-white font-semibold rounded-lg shadow-md hover:from-[#B2183A] hover:to-[#ED4A69] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#ED4A69] transition">
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
