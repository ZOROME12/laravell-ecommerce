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
    @vite(['resources/css/app.css', 'resources/js/app.js'])
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
            <!-- Subtle decorative element -->
            <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-[#FBB3C8]/20 to-[#ED4A69]/10 rounded-bl-full -mr-16 -mt-16"></div>
            <h1 class="text-center text-3xl font-bold mb-6 text-[#3F1A2B] relative z-10">Welcome Back</h1>
            {{ $slot }}
        </div>

        <!-- Footer -->
        <p class="mt-8 text-sm text-gray-600 text-center">
            Powered by <span class="text-[#B2183A] font-semibold">EASEPrint</span>
        </p>
    </div>
</body>
</html>