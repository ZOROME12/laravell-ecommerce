<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'EASEPrint') }}</title>

    <!-- Fonts -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Fixed Font Awesome (CDN) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-vD1Y4aVxMbEIXxY8ulv+R+QlqJzI7cJjMx+d89cwMTQbW6d6vF9eThL07m38gLpI5Q8U63RU7S9E4xh6UudfTg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body class="font-sans antialiased bg-[#FBF8FB]">
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">

        <!-- Top Gradient Header -->
        <div class="w-full bg-gradient-to-r from-[#3F1A2B] via-[#B2183A] to-[#ED4A69] py-6 flex justify-center shadow-lg">
            <img src="/image/easeP.jpg" alt="EASEPrint Logo"
                 style="width: 60px; height: auto; max-height: 60px;"
                 class="rounded-full border-4 border-white shadow-lg bg-white p-1">
        </div>

        <!-- Card -->
        <div class="w-full sm:max-w-md mt-6 px-6 py-8 bg-white shadow-2xl rounded-3xl border-t-4 border-[#ED4A69] relative overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-[#FBB3C8]/20 to-[#ED4A69]/10 rounded-bl-full -mr-16 -mt-16"></div>

            <!-- LOGIN -->
            @if (request()->is('login'))
                <h1 class="text-center text-3xl font-bold mb-6 text-[#3F1A2B] relative z-10">Welcome Back</h1>
                <form method="POST" action="{{ route('login') }}" class="space-y-5 relative z-10">
                    @csrf

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm text-[#3F1A2B] mb-1">Email</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                                <i class="fa-solid fa-envelope text-[#B2183A] text-sm"></i>
                            </span>
                            <input id="email" type="email" name="email" required autofocus
                                class="w-full border rounded-lg pl-10 pr-3 py-2 text-sm focus:ring-2 focus:ring-[#ED4A69] focus:border-[#ED4A69]"
                                placeholder="Enter your email">
                        </div>
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm text-[#3F1A2B] mb-1">Password</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                                <i class="fa-solid fa-lock text-[#B2183A] text-sm"></i>
                            </span>
                            <input id="password" type="password" name="password" required
                                class="w-full border rounded-lg pl-10 pr-3 py-2 text-sm focus:ring-2 focus:ring-[#ED4A69] focus:border-[#ED4A69]"
                                placeholder="Enter your password">
                        </div>
                        @if (Route::has('password.request'))
                            <a class="text-sm text-[#B2183A] hover:underline mt-2 inline-block" href="{{ route('password.request') }}">
                                Forgot your password?
                            </a>
                        @endif
                    </div>

                    <!-- Submit -->
                    <button type="submit"
                        class="bg-[#B2183A] hover:bg-[#ED4A69] text-white w-full py-2.5 rounded-lg text-base transition">
                        Login
                    </button>

                    <!-- Divider -->
                    <div class="flex items-center my-4">
                        <hr class="flex-grow border-gray-300">
                        <span class="mx-2 text-gray-400 text-sm">OR</span>
                        <hr class="flex-grow border-gray-300">
                    </div>

                    <!-- Google Login -->
                    <a href="{{ route('auth.google.redirect') }}"
                       class="flex items-center justify-center gap-3 w-full rounded-lg py-2.5 text-base text-gray-700 bg-white border border-gray-300 shadow-sm hover:shadow-md hover:bg-gray-50 transition">
                        <img src="https://www.gstatic.com/firebasejs/ui/2.0.0/images/auth/google.svg"
                             alt="Google Logo" class="w-5 h-5">
                        <span>Continue with Google</span>
                    </a>

                    <!-- Register Link -->
                    <div class="text-center text-sm text-[#3F1A2B] mt-4">
                        Don't have an account?
                        <a href="{{ route('register') }}" class="text-[#B2183A] hover:underline">Sign up</a>
                    </div>
                </form>
            @endif

            <!-- REGISTER -->
            @if (request()->is('register'))
                <h1 class="text-center text-3xl font-bold mb-6 text-[#3F1A2B] relative z-10">Create Account</h1>
                <form method="POST" action="{{ route('register') }}" class="space-y-5 relative z-10">
                    @csrf

                    <!-- Full Name -->
                    <div>
                        <label for="name" class="block text-sm text-[#3F1A2B] mb-1">Full Name</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                                <i class="fa-solid fa-user text-[#B2183A] text-sm"></i>
                            </span>
                            <input id="name" type="text" name="name" required autofocus
                                class="w-full border rounded-lg pl-10 pr-3 py-2 text-sm focus:ring-2 focus:ring-[#ED4A69] focus:border-[#ED4A69]"
                                placeholder="Enter your full name">
                        </div>
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm text-[#3F1A2B] mb-1">Email</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                                <i class="fa-solid fa-envelope text-[#B2183A] text-sm"></i>
                            </span>
                            <input id="email" type="email" name="email" required
                                class="w-full border rounded-lg pl-10 pr-3 py-2 text-sm focus:ring-2 focus:ring-[#ED4A69] focus:border-[#ED4A69]"
                                placeholder="Enter your email">
                        </div>
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm text-[#3F1A2B] mb-1">Password</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                                <i class="fa-solid fa-lock text-[#B2183A] text-sm"></i>
                            </span>
                            <input id="password" type="password" name="password" required
                                class="w-full border rounded-lg pl-10 pr-3 py-2 text-sm focus:ring-2 focus:ring-[#ED4A69] focus:border-[#ED4A69]"
                                placeholder="Enter your password">
                        </div>
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label for="password_confirmation" class="block text-sm text-[#3F1A2B] mb-1">Confirm Password</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                                <i class="fa-solid fa-lock text-[#B2183A] text-sm"></i>
                            </span>
                            <input id="password_confirmation" type="password" name="password_confirmation" required
                                class="w-full border rounded-lg pl-10 pr-3 py-2 text-sm focus:ring-2 focus:ring-[#ED4A69] focus:border-[#ED4A69]"
                                placeholder="Re-enter your password">
                        </div>
                    </div>

                    <!-- Submit -->
                    <button type="submit"
                        class="bg-[#B2183A] hover:bg-[#ED4A69] text-white w-full py-2.5 rounded-lg text-base transition">
                        Register
                    </button>

                    <!-- Divider -->
                    <div class="flex items-center my-4">
                        <hr class="flex-grow border-gray-300">
                        <span class="mx-2 text-gray-400 text-sm">OR</span>
                        <hr class="flex-grow border-gray-300">
                    </div>

                    <!-- Google Register -->
                    <a href="{{ route('auth.google.redirect') }}"
                       class="flex items-center justify-center gap-3 w-full rounded-lg py-2.5 text-base text-gray-700 bg-white border border-gray-300 shadow-sm hover:shadow-md hover:bg-gray-50 transition">
                        <img src="https://www.gstatic.com/firebasejs/ui/2.0.0/images/auth/google.svg"
                             alt="Google Logo" class="w-5 h-5">
                        <span>Sign up with Google</span>
                    </a>

                    <!-- Login Link -->
                    <div class="text-center text-sm text-[#3F1A2B] mt-4">
                        Already have an account?
                        <a href="{{ route('login') }}" class="text-[#B2183A] hover:underline">Login</a>
                    </div>
                </form>
            @endif

            <!-- FORGOT PASSWORD -->
            @if (request()->is('forgot-password') || request()->is('password/reset') || request()->is('password/request'))
                <h1 class="text-center text-3xl font-bold mb-6 text-[#3F1A2B] relative z-10">Reset Password</h1>
                <form method="POST" action="{{ route('password.email') }}" class="space-y-5 relative z-10">
                    @csrf

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm text-[#3F1A2B] mb-1">Email</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                                <i class="fa-solid fa-envelope text-[#B2183A] text-sm"></i>
                            </span>
                            <input id="email" type="email" name="email" required autofocus
                                class="w-full border rounded-lg pl-10 pr-3 py-2 text-sm focus:ring-2 focus:ring-[#ED4A69] focus:border-[#ED4A69]"
                                placeholder="Enter your email">
                        </div>
                        @error('email')
                            <span class="text-sm text-red-500 mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Submit -->
                    <button type="submit"
                        class="bg-[#B2183A] hover:bg-[#ED4A69] text-white w-full py-2.5 rounded-lg text-base transition">
                        Send Password Reset Link
                    </button>

                    <!-- Back to login -->
                    <div class="text-center text-sm text-[#3F1A2B] mt-4">
                        Remembered your password?
                        <a href="{{ route('login') }}" class="text-[#B2183A] hover:underline">Login</a>
                    </div>
                </form>
            @endif
        </div>

        <!-- Footer -->
        <p class="mt-8 text-sm text-gray-600 text-center">
            Powered by <span class="text-[#B2183A] font-semibold">EASEPrint</span>
        </p>
    </div>

    <script>
        
    // Prevent back navigation after login
    (function () {
        if (window.history && window.history.pushState) {
            history.pushState(null, null, location.href);
            window.onpopstate = function () {
                history.pushState(null, null, location.href);
            };
        }
    })();

    </script>

</body>
</html>
