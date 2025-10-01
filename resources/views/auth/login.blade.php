<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4 text-sm text-green-600" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" class="text-[#3F1A2B] font-semibold" />
            <x-text-input id="email"
                class="block mt-1 w-full rounded-lg border-gray-300 focus:border-[#B2183A] focus:ring-[#ED4A69] bg-[#FBF8FB]"
                type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-[#ED4A69]" />
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Password')" class="text-[#3F1A2B] font-semibold" />
            <x-text-input id="password"
                class="block mt-1 w-full rounded-lg border-gray-300 focus:border-[#B2183A] focus:ring-[#ED4A69] bg-[#FBF8FB]"
                type="password" name="password" required autocomplete="current-password" />
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
