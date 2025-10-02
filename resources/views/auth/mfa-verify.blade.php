<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'EASEPrint') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
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

            <!-- MFA FORM -->
            <h1 class="text-center text-3xl font-bold mb-6 text-[#3F1A2B] relative z-10">Two-Factor Verification</h1>
            <p class="mb-4 text-gray-600 text-center relative z-10">We sent a 6-digit code to your email. Enter it below:</p>

            <form method="POST" action="{{ route('mfa.verify') }}" class="space-y-5 relative z-10">
                @csrf
                <input type="hidden" name="email" value="{{ $email }}">

                <!-- Verification Code -->
                <div>
                    <label for="code" class="block text-sm text-primary mb-1">Verification Code</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-accent">
                            <!-- Shield Check Icon -->
                            <svg xmlns="http://www.w3.org/2000/svg" 
                                class="h-5 w-5" 
                                fill="none" 
                                viewBox="0 0 24 24" 
                                stroke="currentColor" 
                                stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" 
                                    d="M12 6l7 4v6c0 5-7 8-7 8s-7-3-7-8V10l7-4z" />
                                <path stroke-linecap="round" stroke-linejoin="round" 
                                    d="M9 12l2 2 4-4" />
                            </svg>
                        </span>
                        <input 
                            type="text" 
                            name="code" 
                            id="code"
                            class="w-full border rounded-lg pl-10 pr-3 py-2 text-sm focus:ring-2 focus:ring-accent focus:border-accent" 
                            placeholder="Enter 6-digit code"
                            required
                        >
                    </div>
                    @error('code') 
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p> 
                    @enderror
                </div>


                <!-- Submit -->
                <button type="submit"
                    class="bg-[#B2183A] hover:bg-[#ED4A69] text-white w-full py-2.5 rounded-lg text-base transition">
                    Verify
                </button>
            </form>
        </div>

        <!-- Footer -->
        <p class="mt-8 text-sm text-gray-600 text-center">
            Powered by <span class="text-[#B2183A] font-semibold">EASEPrint</span>
        </p>
    </div>
</body>
</html>
