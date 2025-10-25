<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- === NEW NO-CACHE HEADERS === -->
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <!-- === END NO-CACHE HEADERS === -->

    <title>{{ config('app.name', 'EASEPrint') }}</title>

    <!-- Fonts -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Fixed Font Awesome (CDN) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" xintegrity="sha512-vD1Y4aVxMbEIXxY8ulv+R+QlqJzI7cJjMx+d89cwMTQbW6d6vF9eThL07m38gLpI5Q8U63RU7S9E4xh6UudfTg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body class="font-sans antialiased bg-[#FBF8FB]">

    <!-- === STYLISH SPAM ALERT (HIDDEN BY DEFAULT) === -->
    <div id="spamAlert" class="hidden fixed top-5 left-1/2 -translate-x-1/2 bg-red-600 text-white px-6 py-3 rounded-lg shadow-lg z-50 transition-all duration-300 opacity-0">
        Action in progress. Please wait.
    </div>

    <!-- === MODIFICATION: Removed sm:justify-center, added padding for responsive height === -->
    <div class="min-h-screen flex flex-col items-center pt-6 pb-6 sm:pt-12 sm:pb-12">

        <!-- Top Gradient Header -->
        <div class="w-full bg-gradient-to-r from-[#3F1A2B] via-[#B2183A] to-[#ED4A69] py-6 flex justify-center shadow-lg">
            <img src="/image/easeP.jpg" alt="EASEPrint Logo"
                 style="width: 60px; height: auto; max-height: 60px;"
                 class="rounded-full border-4 border-white shadow-lg bg-white p-1">
        </div>

        <!-- Card -->
        <!-- === MODIFICATION: Added mt-8 to separate card from header === -->
        <div class="w-full sm:max-w-md mt-8 px-6 py-8 bg-white shadow-2xl rounded-3xl border-t-4 border-[#ED4A69] relative overflow-visible">
            <!-- LOGIN -->
            @if (request()->is('login'))
                <h1 class="text-center text-3xl font-bold mb-6 text-[#3F1A2B] relative z-10">Welcome Back</h1>
                <!-- === ADDED ID 'loginForm' === -->
                <form method="POST" action="{{ route('login') }}" class="space-y-5 relative z-10" id="loginForm">
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
                                   placeholder="Enter your email" autocomplete="off"> <!-- Added autocomplete=off -->
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
                                   placeholder="Enter your password" autocomplete="off"> <!-- Added autocomplete=off -->
                        </div>
                        @if (Route::has('password.request'))
                            <a class="text-sm text-[#B2183A] hover:underline mt-2 inline-block" href="{{ route('password.request') }}">
                                Forgot your password?
                            </a>
                        @endif
                    </div>

                    <!-- Submit -->
                    <!-- === ADDED ID 'loginButton' === -->
                    <button type="submit" id="loginButton"
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
                <!-- === ADDED ID 'registerForm' === -->
                <form method="POST" action="{{ route('register') }}" class="space-y-5 relative z-10" id="registerForm">
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
                    <!-- === ADDED relative to parent div for popover positioning === -->
                    <div class="relative">
                        <label for="password" class="block text-sm text-[#3F1A2B] mb-1">Password</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                                <i class="fa-solid fa-lock text-[#B2183A] text-sm"></i>
                            </span>
                            <!-- pattern and title added for basic browser validation -->
                            <input id="password" type="password" name="password" required
                                   class="w-full border rounded-lg pl-10 pr-3 py-2 text-sm focus:ring-2 focus:ring-[#ED4A69] focus:border-[#ED4A69]"
                                   placeholder="Enter your password"
                                   pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[\W_]).{8,}$"
                                   title="Must be 8+ characters, with at least 1 uppercase, 1 lowercase, 1 number, and 1 symbol.">
                        </div>

                        <!-- === MODIFICATION: Floating Password Requirements Popover (Responsive) === -->
                        <div id="password-popover"
                             class="hidden opacity-0 transition-all duration-300 w-full mt-4 p-4 bg-white rounded-lg shadow-lg border border-gray-200 md:absolute md:z-20 md:left-full md:top-1/2 md:-translate-y-1/2 md:ml-4 md:w-72 md:mt-0 md:shadow-xl">
                            <!-- Arrow pointing left (hidden on mobile) -->
                            <div class="hidden md:block absolute -left-2 top-1/2 -translate-y-1/2 w-4 h-4 bg-white border-l border-b border-gray-200 transform rotate-45"></div>

                            <p class="font-semibold text-sm text-[#3F1A2B] mb-3">Password must contain:</p>
                            <ul class="space-y-1.5 text-sm">
                                <li id="req-length" class="flex items-center text-red-500">
                                    <i id="req-icon-length" class="fa-solid fa-xmark w-4 h-4 mr-2"></i>
                                    <span id="req-text-length">At least 8 characters</span>
                                </li>
                                <li id="req-lower" class="flex items-center text-red-500">
                                    <i id="req-icon-lower" class="fa-solid fa-xmark w-4 h-4 mr-2"></i>
                                    <span id="req-text-lower">At least 1 lowercase letter (a-z)</span>
                                </li>
                                <li id="req-upper" class="flex items-center text-red-500">
                                    <i id="req-icon-upper" class="fa-solid fa-xmark w-4 h-4 mr-2"></i>
                                    <span id="req-text-upper">At least 1 uppercase letter (A-Z)</span>
                                </li>
                                <li id="req-number" class="flex items-center text-red-500">
                                    <i id="req-icon-number" class="fa-solid fa-xmark w-4 h-4 mr-2"></i>
                                    <span id="req-text-number">At least 1 number (0-9)</span>
                                </li>
                                <li id="req-symbol" class="flex items-center text-red-500">
                                    <i id="req-icon-symbol" class="fa-solid fa-xmark w-4 h-4 mr-2"></i>
                                    <span id="req-text-symbol">At least 1 special character (@, #, $, etc.)</span>
                                </li>
                            </ul>
                        </div>
                        <!-- === END MODIFICATION === -->
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
                        <!-- === UPDATED: Password Match Message (with icon) === -->
                        <div id="confirm-password-message" class="mt-1 text-sm font-medium h-5 flex items-center">
                            <!-- Content injected by JS -->
                        </div>
                        <!-- === END UPDATED === -->
                    </div>

                    <!-- === NEW: Terms and Conditions Checkbox === -->
                    <div class_="">
                        <input type="checkbox" id="terms" name="terms" class="rounded border-gray-300 text-[#B2183A] shadow-sm focus:ring-[#ED4A69]">
                        <label for="terms" class="ml-2 text-sm text-[#3F1A2B]">
                            I agree to the
                            <!-- === MODIFICATION: Changed <a> to <button> to trigger modal === -->
                            <button type="button" id="open-terms-modal" class="text-[#B2183A] hover:underline focus:outline-none">
                                Terms and Conditions
                            </button>
                        </label>
                    </div>
                    <!-- === END NEW === -->


                    <!-- Submit -->
                    <!-- === ADDED ID 'registerButton' === -->
                    <button type="submit" id="registerButton"
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
                    <!-- === NEW: Added ID 'googleRegisterLink' === -->
                    <a id="googleRegisterLink" href="{{ route('auth.google.redirect') }}"
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
                <!-- === ADDED ID 'forgotForm' === -->
                <form method="POST" action="{{ route('password.email') }}" class="space-y-5 relative z-10" id="forgotForm">
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
                    <!-- === ADDED ID 'forgotButton' === -->
                    <button type="submit" id="forgotButton"
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

    <!-- === NEW: Terms and Conditions Modal === -->
    <div id="terms-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 p-4 transition-opacity duration-300">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-2xl max-h-[90vh] flex flex-col">
            <!-- Modal Header -->
            <div class="flex justify-between items-center p-5 border-b">
                <h2 class="text-2xl font-bold text-[#3F1A2B]">Terms and Conditions</h2>
                <button id="terms-modal-close-x" type="button" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <i class="fa-solid fa-xmark fa-2x"></i>
                </button>
            </div>
            <!-- Modal Body (Scrollable) -->
            <div class="p-6 space-y-4 overflow-y-auto">
                <p>Welcome to EaseFlow, the online ordering and workflow management system for Ease Print. These Terms and Conditions ("Terms") govern your access to and use of the EaseFlow system, including the website, mobile application, and any related services (collectively, the "System").</p>
                <p>Please read these Terms carefully before creating an account. By creating an account or using the System, you agree to be bound by these Terms. If you do not agree to these Terms, do not create an account or use the System.</p>

                <h3 class="font-bold text-lg text-[#3F1A2B] pt-2">Your Account</h3>
                <p><strong>Account Creation:</strong> You must provide accurate, current, and complete information during the account registration process.</p>
                <p><strong>Account Security:</strong> You are responsible for safeguarding your account password. You agree not to disclose your password to any third party. You must notify Ease Print immediately of any unauthorized use of your account.</p>
                <p><strong>Responsibility:</strong> You are responsible for all activities or actions that occur under your account, whether or not you have authorized them.</p>

                <h3 class="font-bold text-lg text-[#3F1A2B] pt-2">Use of the System</h3>
                <p><strong>Permitted Use:</strong> You agree to use the System only for its intended purposes. This includes placing custom print orders, uploading design files, managing your account, viewing order history, and communicating with Ease Print.</p>
                <p><strong>Prohibited Use:</strong> You agree not to:</p>
                <ul class="list-disc list-inside pl-4 space-y-1 text-sm">
                    <li>Use the System for any illegal or unauthorized purpose.</li>
                    <li>Upload, post, or transmit any content (e.g., designs, text) that infringes upon any third party's intellectual property rights, privacy rights, or other rights.</li>
                    <li>Upload any content that is unlawful, harmful, threatening, abusive, or obscene.</li>
                    <li>Attempt to reverse engineer, decompile, or otherwise access the source code of the System.</li>
                    <li>Interfere with or disrupt the integrity or performance of the System or the data contained within.</li>
                </ul>

                <h3 class="font-bold text-lg text-[#3F1A2B] pt-2">Orders and Content</h3>
                <p><strong>Your Content:</strong> You retain all ownership rights to the designs, images, and other content you upload to the System ("Your Content").</p>
                <p><strong>License to Ease Print:</strong> By uploading Your Content, you grant Ease Print a limited, non-exclusive, worldwide, royalty-free license to use, reproduce, modify, and display Your Content. This license is solely for the purpose of fulfilling your print orders and providing the services you request.</p>
                <p><strong>Content Review:</strong> Ease Print reserves the right, but not the obligation, to review Your Content. We may refuse to print any content that we determine, in our sole discretion, violates these Terms or is otherwise objectionable.</p>
                <p><strong>Order Acceptance:</strong> All orders placed through the System are subject to acceptance by Ease Print. We may refuse or cancel an order for any reason, including limitations on materials, errors in product or pricing information, or issues identified with Your Content.</p>
                <p><strong>Payment:</strong> You agree to pay all charges associated with your orders, including product costs, taxes, and any applicable shipping or delivery fees.</p>

                <h3 class="font-bold text-lg text-[#3F1A2B] pt-2">System Ownership</h3>
                <p>The System itself, including all software, text, graphics, logos, and user interfaces, is the exclusive property of Ease Print and its licensors. This property is protected by copyright and other intellectual property laws.</p>

                <h3 class="font-bold text-lg text-[#3F1A2B] pt-2">Privacy</h3>
                <p>Your use of the System is also governed by our Privacy Policy. The Privacy Policy describes how we collect, use, and protect your personal information. By creating an account, you consent to the data practices described in the Privacy Policy.</p>

                <h3 class="font-bold text-lg text-[#3F1A2B] pt-2">Termination</h3>
                <p><strong>Termination by You:</strong> You can terminate your account at any time by contacting Ease Print customer service or through your account settings, if available.</p>
                <p><strong>Termination by Ease Print:</strong> Ease Print may suspend or terminate your account and access to the System at any time, without prior notice or liability, for any reason. This includes, but is not limited to, a breach of these Terms.</p>

                <h3 class="font-bold text-lg text-[#3F1A2B] pt-2">Disclaimers</h3>
                <p>The System is provided "AS IS," without warranty of any kind, either express or implied. Ease Print does not warrant that the System will be uninterrupted, secure, or error-free.</p>

                <h3 class="font-bold text-lg text-[#3F1A2B] pt-2">Limitation of Liability</h3>
                <p>To the maximum extent permitted by law, Ease Print shall not be liable for any indirect, incidental, special, consequential, or punitive damages resulting from your access to or use of, or inability to access or use, the System or any content.</p>

                <h3 class="font-bold text-lg text-[#3F1A2B] pt-2">Changes to Terms</h3>
                <p>Ease Print reserves the right to modify these Terms at any time. We will provide notice of modifications by posting the revised Terms on the System or by other means. Your continued use of the System after such modifications constitutes your acceptance of the new Terms.</p>

                <h3 class="font-bold text-lg text-[#3F1A2B] pt-2">Governing Law</h3>
                <p>These Terms shall be governed by the laws of the jurisdiction where Ease Print operates, without regard to its conflict of law provisions.</p>

                <h3 class="font-bold text-lg text-[#3F1A2B] pt-2">Contact</h3>
                <p>If you have any questions about these Terms, please contact Ease Print.</p>
                <p class="font-bold pt-2">By checking the box and creating an account, you acknowledge that you have read, understood, and agree to be bound by these Terms and Conditions.</p>
            </div>
            <!-- Modal Footer -->
            <div class="p-4 border-t bg-gray-50 rounded-b-2xl">
                <button id="terms-modal-close-btn" type="button" class="w-full sm:w-auto sm:float-right bg-[#B2183A] hover:bg-[#ED4A69] text-white px-6 py-2 rounded-lg text-base transition">
                    Close
                </button>
            </div>
        </div>
    </div>
    <!-- === END Modal === -->


    <script>

    // === REMOVED PROBLEMATIC HISTORY SCRIPT ===

    // === UPDATED SCRIPT TO RESET FORMS ON *ANY* PAGE SHOW (BFCACHE OR RELOAD) ===
    window.addEventListener('pageshow', function (event) {
        // We run this on *every* page show, not just from bfcache (event.persisted)
        // This clears the form on back-button presses AND reloads.

        const loginForm = document.getElementById('loginForm');
        const registerForm = document.getElementById('registerForm');
        const forgotForm = document.getElementById('forgotForm');

        const loginButton = document.getElementById('loginButton');
        const registerButton = document.getElementById('registerButton');
        const forgotButton = document.getElementById('forgotButton');

        // Reset all form fields
        if (loginForm) loginForm.reset();
        if (registerForm) registerForm.reset();
        if (forgotForm) forgotForm.reset();

        // Re-enable login button and reset text
        if (loginButton) {
            loginButton.disabled = false;
            loginButton.innerHTML = 'Login';
        }
        // Re-enable register button and reset text
        if (registerButton) {
            registerButton.disabled = false;
            registerButton.innerHTML = 'Register';
            // Also reset terms checkbox
            const termsCheckbox = document.getElementById('terms');
            if (termsCheckbox) termsCheckbox.checked = false;
            // Manually re-disable buttons (as they would be on load)
            const googleLink = document.getElementById('googleRegisterLink');
            registerButton.disabled = true;
            if (googleLink) googleLink.classList.add('opacity-50', 'pointer-events-none');
        }
        // Re-enable forgot button and reset text
        if (forgotButton) {
            forgotButton.disabled = false;
            forgotButton.innerHTML = 'Send Password Reset Link';
        }
    });

    // === SCRIPT TO PREVENT SPAM-CLICKING (NEW VERSION FOR ALL FORMS) ===
    document.addEventListener('DOMContentLoaded', function () {

        // === NEW: Modal Open/Close Logic ===
        const termsModal = document.getElementById('terms-modal');
        const openTermsBtn = document.getElementById('open-terms-modal');
        const closeTermsX = document.getElementById('terms-modal-close-x');
        const closeTermsBtn = document.getElementById('terms-modal-close-btn');

        if (termsModal && openTermsBtn && closeTermsX && closeTermsBtn) {
            // Function to open
            const openModal = () => {
                termsModal.classList.remove('hidden');
            };
            // Function to close
            const closeModal = () => {
                termsModal.classList.add('hidden');
            };

            // Event Listeners
            openTermsBtn.addEventListener('click', openModal);
            closeTermsX.addEventListener('click', closeModal);
            closeTermsBtn.addEventListener('click', closeModal);

            // Close on backdrop click
            termsModal.addEventListener('click', (e) => {
                if (e.target === termsModal) {
                    closeModal();
                }
            });
        }
        // === END Modal Logic ===


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

                    // === Validation check before submit (Register Form) ===
                    if (formId === 'registerForm') {
                        const passInput = document.getElementById('password');
                        const confirmInput = document.getElementById('password_confirmation');
                        const termsCheckbox = document.getElementById('terms'); // Get terms checkbox

                        // Strong password regex
                        const strongPassRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[\W_]).{8,}$/;

                        if (!passInput || !confirmInput || !termsCheckbox) return; // safety check

                        const pass = passInput.value;
                        const confirmPass = confirmInput.value;

                        // Check Terms
                        if (!termsCheckbox.checked) {
                            e.preventDefault();
                            showAlert('You must agree to the Terms and Conditions.');
                            clickCount = 0; // Reset click count
                            return;
                        }

                        if (pass !== confirmPass) {
                            e.preventDefault();
                            showAlert('Passwords do not match. Please correct them.');
                            clickCount = 0; // Reset click count
                            return;
                        }

                        if (!strongPassRegex.test(pass)) {
                            e.preventDefault();
                            showAlert('Password does not meet all requirements.');
                            clickCount = 0; // Reset click count
                            return;
                        }
                    }
                    // === END Validation check ===


                    clickCount++;

                    if (clickCount > 1) {
                        e.preventDefault(); // Stop spam submissions
                        showAlert('Action already in progress. Please wait.');
                        // Keep button disabled, don't reset click count here
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

        // --- Apply to all three forms ---
        setupFormSpamPrevention('loginForm', 'loginButton', 'Logging in');
        setupFormSpamPrevention('registerForm', 'registerButton', 'Registering');
        setupFormSpamPrevention('forgotForm', 'forgotButton', 'Sending');


        // --- Password Strength Live Validation (Popover Version) ---
        const registerFormElem = document.getElementById('registerForm');
        if (registerFormElem) {
            const passwordInput = document.getElementById('password');
            const confirmInput = document.getElementById('password_confirmation');
            const popover = document.getElementById('password-popover');
            // === NEW: Get Terms and Conditions elements ===
            const termsCheckbox = document.getElementById('terms');
            const registerButton = document.getElementById('registerButton');
            const googleLink = document.getElementById('googleRegisterLink');

            // Safety check for popover
            if (!passwordInput || !confirmInput || !popover || !termsCheckbox || !registerButton || !googleLink) return;

            // === NEW: Disable buttons on load ===
            registerButton.disabled = true;
            googleLink.classList.add('opacity-50', 'pointer-events-none');

            // === NEW: Add event listener for Terms Checkbox ===
            termsCheckbox.addEventListener('change', function() {
                if (this.checked) {
                    registerButton.disabled = false;
                    googleLink.classList.remove('opacity-50', 'pointer-events-none');
                } else {
                    registerButton.disabled = true;
                    googleLink.classList.add('opacity-50', 'pointer-events-none');
                }
            });

            const reqs = {
                length: { li: document.getElementById('req-length'), icon: document.getElementById('req-icon-length'), text: document.getElementById('req-text-length') },
                lower: { li: document.getElementById('req-lower'), icon: document.getElementById('req-icon-lower'), text: document.getElementById('req-text-lower') },
                upper: { li: document.getElementById('req-upper'), icon: document.getElementById('req-icon-upper'), text: document.getElementById('req-text-upper') },
                number: { li: document.getElementById('req-number'), icon: document.getElementById('req-icon-number'), text: document.getElementById('req-text-number') },
                symbol: { li: document.getElementById('req-symbol'), icon: document.getElementById('req-icon-symbol'), text: document.getElementById('req-text-symbol') }
            };
            const confirmMsg = document.getElementById('confirm-password-message');

            const regex = {
                lower: /[a-z]/,
                upper: /[A-Z]/,
                number: /[0-9]/,
                symbol: /[\W_]/
            };

            const validIconClass = 'fa-solid fa-check';
            const invalidIconClass = 'fa-solid fa-xmark';
            const validColorClass = 'text-green-600';
            const invalidColorClass = 'text-red-500';

            // Function to update one requirement's icon and text color
            function updateRequirementUI(req, isValid) {
                if (isValid) {
                    req.icon.className = `${validIconClass} w-4 h-4 mr-2`;
                    req.li.className = `flex items-center ${validColorClass}`;
                } else {
                    req.icon.className = `${invalidIconClass} w-4 h-4 mr-2`;
                    req.li.className = `flex items-center ${invalidColorClass}`;
                }
            }

            // Function to validate all password requirements
            function validatePassword() {
                const pass = passwordInput.value;
                updateRequirementUI(reqs.length, pass.length >= 8);
                updateRequirementUI(reqs.lower, regex.lower.test(pass));
                updateRequirementUI(reqs.upper, regex.upper.test(pass));
                updateRequirementUI(reqs.number, regex.number.test(pass));
                updateRequirementUI(reqs.symbol, regex.symbol.test(pass));
            }

            // Function to check if passwords match
            function checkPasswordMatch() {
                const pass = passwordInput.value;
                const confirmPass = confirmInput.value;

                if (confirmPass.length === 0) {
                    confirmMsg.innerHTML = ''; // Clear message if confirm field is empty
                    return;
                }

                if (pass === confirmPass) {
                    confirmMsg.innerHTML = `<i class="fa-solid fa-circle-check mr-1 ${validColorClass}"></i> <span class="${validColorClass}">Passwords match!</span>`;
                } else {
                    confirmMsg.innerHTML = `<i class="fa-solid fa-circle-xmark mr-1 ${invalidColorClass}"></i> <span class="${invalidColorClass}">Passwords do not match.</span>`;
                }
            }

            // Event Listeners
            // Show popover on focus, validate immediately
            passwordInput.addEventListener('focus', () => {
                popover.classList.remove('hidden');
                // Use setTimeout to trigger opacity transition
                setTimeout(() => popover.classList.remove('opacity-0'), 10);
                validatePassword();
            });
            // Hide popover on blur
            passwordInput.addEventListener('blur', () => {
                 popover.classList.add('opacity-0');
                 // Use setTimeout to hide *after* transition
                 setTimeout(() => popover.classList.add('hidden'), 300);
            });
            // Validate password strength and match on password input keyup
            passwordInput.addEventListener('keyup', () => {
                validatePassword();
                checkPasswordMatch(); // Check match as original password changes
            });
            // Check match on confirm password input keyup
            confirmInput.addEventListener('keyup', checkPasswordMatch);
        }
        // --- END Password Strength ---

    });

    </script>

</body>
</html>

