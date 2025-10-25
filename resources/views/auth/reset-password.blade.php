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
    <script src="https://kit.fontawesome.com/64d58efce2.js" crossorigin="anonymous"></script>
</head>
<body class="font-sans antialiased bg-[#FBF8FB]">

    <!-- === STYLISH SPAM ALERT (HIDDEN BY DEFAULT) === -->
    <div id="spamAlert" class="hidden fixed top-5 left-1/2 -translate-x-1/2 bg-red-600 text-white px-6 py-3 rounded-lg shadow-lg z-50 transition-all duration-300 opacity-0">
        Action in progress. Please wait.
    </div>

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

            <!-- RESET PASSWORD -->
            <h1 class="text-center text-3xl font-bold mb-6 text-[#3F1A2B] relative z-10">Reset Password</h1>

            <!-- === ADDED ID 'resetPasswordForm' === -->
            <form method="POST" action="{{ route('password.store') }}" class="space-y-5 relative z-10" id="resetPasswordForm">
                @csrf

                <!-- Password Reset Token -->
                <input type="hidden" name="token" value="{{ $token }}">

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm text-[#3F1A2B] mb-1">Email</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-[#B2183A]">
                            <i class="fas fa-envelope"></i>
                        </span>
                        <input id="email" type="email" name="email"
                               value="{{ old('email', $email) }}"
                               required autofocus autocomplete="username"
                               class="w-full border rounded-lg pl-10 pr-3 py-2 text-sm focus:ring-2 focus:ring-[#ED4A69] focus:border-[#ED4A69]">
                    </div>
                    @error('email')
                        <span class="text-sm text-red-500 mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm text-[#3F1A2B] mb-1">Password</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-[#B2183A]">
                            <i class="fas fa-lock"></i>
                        </span>
                        <input id="password" type="password" name="password"
                               required autocomplete="new-password"
                               class="w-full border rounded-lg pl-10 pr-3 py-2 text-sm focus:ring-2 focus:ring-[#ED4A69] focus:border-[#ED4A69]">
                    </div>
                    @error('password')
                        <span class="text-sm text-red-500 mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="password_confirmation" class="block text-sm text-[#3F1A2B] mb-1">Confirm Password</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-[#B2183A]">
                            <i class="fas fa-lock"></i>
                        </span>
                        <input id="password_confirmation" type="password" name="password_confirmation"
                               required autocomplete="new-password"
                               class="w-full border rounded-lg pl-10 pr-3 py-2 text-sm focus:ring-2 focus:ring-[#ED4A69] focus:border-[#ED4A69]">
                    </div>
                    @error('password_confirmation')
                        <span class="text-sm text-red-500 mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Submit -->
                <!-- === ADDED ID 'resetPasswordButton' === -->
                <button type="submit" id="resetPasswordButton"
                        class="bg-[#B2183A] hover:bg-[#ED4A69] text-white w-full py-2.5 rounded-lg text-base transition">
                    Reset Password
                </button>
            </form>
        </div>

        <!-- Footer -->
        <p class="mt-8 text-sm text-gray-600 text-center">
            Powered by <span class="text-[#B2183A] font-semibold">EASEPrint</span>
        </p>
    </div>

    <!-- === SCRIPT TO PREVENT SPAM-CLICKING === -->
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        
        const spamAlert = document.getElementById('spamAlert');

        // Function to show the alert
        function showAlert(message) {
            if (spamAlert) {
                spamAlert.textContent = message;
                spamAlert.classList.remove('hidden');
                spamAlert.classList.remove('opacity-0');
                // Hide after 3 seconds
                setTimeout(() => {
                    spamAlert.classList.add('opacity-0');
                    setTimeout(() => spamAlert.classList.add('hidden'), 300);
                }, 3000);
            }
        }

        // --- Generic function to handle form submission ---
        function setupFormSpamPrevention(formId, buttonId, loadingText) {
            const form = document.getElementById(formId);
            const button = document.getElementById(buttonId);
            let clickCount = 0;

            if (form && button) {
                form.addEventListener('submit', function (e) {
                    clickCount++;

                    if (clickCount > 1) {
                        e.preventDefault(); // Stop spam submissions
                        showAlert('Action already in progress. Please wait.');
                        button.disabled = true; // Ensure it stays disabled
                        return;
                    }

                    // First valid click
                    button.disabled = true;
                    // Generic spinner (using Font Awesome)
                    button.innerHTML = `
                        <i class="fas fa-spinner fa-spin mr-2"></i>
                        ${loadingText}...
                    `;
                    // Let the form submit normally
                });
            }
        }

        // --- Apply to the Reset Password form ---
        setupFormSpamPrevention('resetPasswordForm', 'resetPasswordButton', 'Resetting');

    });
    </script>
</body>
</html>
