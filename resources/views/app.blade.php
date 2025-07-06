<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'EasePrint') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#3F1A2B',
                        secondary: '#B2183A',
                        accent: '#ED4A69',
                        light: '#FBB3C8',
                        background: '#FBF8FB'
                    }
                }
            }
        }
    </script>
    <style>
        .dropdown:hover .dropdown-menu { display: block; }
        .cart-badge { top: -8px; right: -8px; }
        .product-card { transition: all 0.3s ease; }
        .product-card:hover { transform: translateY(-5px); }
        .size-btn:hover { border-color: #ED4A69; }
        .color-option { transition: all 0.2s ease; }
        .quick-view-btn { 
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        .group:hover .quick-view-btn { opacity: 1; }
        
        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            transition: opacity 0.3s ease;
        }
        
        .modal.show {
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 1;
        }
        
        .modal-content {
            background: white;
            border-radius: 0.5rem;
            width: 100%;
            max-width: 28rem;
            padding: 2rem;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            transform: translateY(20px);
            transition: transform 0.3s ease;
        }
        
        .modal.show .modal-content {
            transform: translateY(0);
        }
    </style>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="bg-background">
    <!-- Header -->
    <header class="bg-primary text-white shadow-lg sticky top-0 z-40">
        <div class="container mx-auto px-4 py-3 flex justify-between items-center">
            <div class="flex items-center space-x-2">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                <span class="text-xl font-bold">Ease Print.</span>
            </div>
            
            <nav class="hidden md:flex space-x-6">
                <a href="#" class="hover:text-accent transition">Home</a>
                <div class="dropdown relative">
                    <button class="hover:text-accent transition flex items-center">
                        Shop <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                    <div class="dropdown-menu absolute hidden bg-white text-primary py-2 shadow-lg rounded-md min-w-[200px]">
                        <a href="{{ route('cart.index') }}" class="block px-4 py-2 hover:bg-light">🛒 My Cart</a>
                        <a href="#" class="block px-4 py-2 hover:bg-light">Basketball</a>
                        <a href="#" class="block px-4 py-2 hover:bg-light">Baseball</a>
                    </div>
                </div>
                <a href="#" class="hover:text-accent transition">About</a>
                <a href="#" class="hover:text-accent transition">Contact</a>
            </nav>
            
            <div class="flex items-center space-x-4">
                <div class="relative">
                    <svg class="w-6 h-6 hover:text-accent cursor-pointer" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                    @if ($cartItemCount > 0)
                        <span class="cart-badge absolute bg-accent text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
                            {{ $cartItemCount }}
                        </span>
                    @endif
                </div>
                <button onclick="openModal('login-modal')" class="bg-secondary hover:bg-accent px-4 py-2 rounded-md transition">Login</button>
                <button onclick="openModal('register-modal')" class="bg-secondary hover:bg-accent px-4 py-2 rounded-md transition">Register</button>
                <button class="md:hidden">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>
        </div>
    </header>

    <!-- Hero Banner -->
    <section class="bg-gradient-to-r from-primary to-secondary text-white py-16">
        <div class="container mx-auto px-4 text-center">
            <h1 class="text-4xl md:text-5xl font-bold mb-4">Premium Team Jerseys</h1>
            <p class="text-xl mb-8 max-w-2xl mx-auto">Authentic jerseys for true fans. Limited editions available!</p>
            <button class="bg-accent hover:bg-light text-primary font-bold px-8 py-3 rounded-lg transition">Shop Now</button>
        </div>
    </section>

    <!-- Main Content -->
    <main class="container mx-auto px-4 py-12">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-primary text-white py-12">
        <div class="container mx-auto px-4 grid md:grid-cols-4 gap-8">
            <div>
                <h3 class="text-xl font-bold mb-4">Ease Print</h3>
                <p>Premium sports apparel for the dedicated fan.</p>
            </div>
            <div>
                <h4 class="font-bold mb-4">Quick Links</h4>
                <ul class="space-y-2">
                    <li><a href="#" class="hover:text-accent transition">About Us</a></li>
                    <li><a href="#" class="hover:text-accent transition">Shipping Policy</a></li>
                    <li><a href="#" class="hover:text-accent transition">Returns</a></li>
                </ul>
            </div>
            <div>
                <h4 class="font-bold mb-4">Customer Service</h4>
                <ul class="space-y-2">
                    <li><a href="#" class="hover:text-accent transition">Contact Us</a></li>
                    <li><a href="#" class="hover:text-accent transition">FAQs</a></li>
                    <li><a href="#" class="hover:text-accent transition">Size Guide</a></li>
                </ul>
            </div>
        </div>
    </footer>

    <!-- Login Modal -->
    <div id="login-modal" class="modal">
        <div class="modal-content">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-primary">Welcome Back!</h2>
                <button onclick="closeModal('login-modal')" class="text-accent hover:text-secondary text-xl">
                    &times;
                </button>
            </div>
            <form id="login-form" action="{{ route('login') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="login-email" class="block text-sm font-medium text-primary mb-1">Email</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-envelope text-accent"></i>
                        </div>
                        <input type="email" name="email" id="login-email" class="w-full pl-10 border rounded-lg px-3 py-2 focus:ring-2 focus:ring-accent focus:border-transparent" required>
                    </div>
                </div>
                <div class="mb-6">
                    <label for="login-password" class="block text-sm font-medium text-primary mb-1">Password</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-lock text-accent"></i>
                        </div>
                        <input type="password" name="password" id="login-password" class="w-full pl-10 border rounded-lg px-3 py-2 focus:ring-2 focus:ring-accent focus:border-transparent" required>
                    </div>
                </div>
                <button type="submit" class="bg-secondary hover:bg-accent text-white w-full py-3 rounded-lg font-bold text-lg mb-4 transition">
                    Login
                </button>
                <div class="text-center text-sm text-primary">
                    Don't have an account? 
                    <a href="#" onclick="closeModal('login-modal'); openModal('register-modal');" class="text-accent hover:underline">Sign up</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Register Modal -->
    <div id="register-modal" class="modal">
        <div class="modal-content">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-primary">Create Account</h2>
                <button onclick="closeModal('register-modal')" class="text-accent hover:text-secondary text-xl">
                    &times;
                </button>
            </div>
            <form id="register-form" action="{{ route('register') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="register-name" class="block text-sm font-medium text-primary mb-1">Full Name</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-user text-accent"></i>
                        </div>
                        <input type="text" name="name" id="register-name" class="w-full pl-10 border rounded-lg px-3 py-2 focus:ring-2 focus:ring-accent focus:border-transparent" required>
                    </div>
                </div>
                <div class="mb-4">
                    <label for="register-email" class="block text-sm font-medium text-primary mb-1">Email</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-envelope text-accent"></i>
                        </div>
                        <input type="email" name="email" id="register-email" class="w-full pl-10 border rounded-lg px-3 py-2 focus:ring-2 focus:ring-accent focus:border-transparent" required>
                    </div>
                </div>
                <div class="mb-4">
                    <label for="register-password" class="block text-sm font-medium text-primary mb-1">Password</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-lock text-accent"></i>
                        </div>
                        <input type="password" name="password" id="register-password" class="w-full pl-10 border rounded-lg px-3 py-2 focus:ring-2 focus:ring-accent focus:border-transparent" required>
                    </div>
                </div>
                <div class="mb-6">
                    <label for="register-password-confirm" class="block text-sm font-medium text-primary mb-1">Confirm Password</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-lock text-accent"></i>
                        </div>
                        <input type="password" name="password_confirmation" id="register-password-confirm" class="w-full pl-10 border rounded-lg px-3 py-2 focus:ring-2 focus:ring-accent focus:border-transparent" required>
                    </div>
                </div>
                <button type="submit" class="bg-secondary hover:bg-accent text-white w-full py-3 rounded-lg font-bold text-lg mb-4 transition">
                    Register
                </button>
                <div class="text-center text-sm text-primary">
                    Already have an account? 
                    <a href="#" onclick="closeModal('register-modal'); openModal('login-modal');" class="text-accent hover:underline">Login</a>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Modal functions
        function openModal(modalId) {
            document.getElementById(modalId).classList.add('show');
            document.body.style.overflow = 'hidden';
        }

        function closeModal(modalId) {
            document.getElementById(modalId).classList.remove('show');
            document.body.style.overflow = 'auto';
        }

        // Form submission handlers
        document.getElementById('login-form').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            try {
                const formData = new FormData(this);
                const response = await fetch(this.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                if (response.redirected) {
                    window.location.href = response.url;
                } else {
                    const result = await response.json();
                    if (response.ok) {
                        window.location.reload();
                    } else {
                        alert(result.message || 'Login failed');
                    }
                }
            } catch (error) {
                console.error('Error:', error);
                alert('An error occurred during login');
            }
        });

        document.getElementById('register-form').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            try {
                const formData = new FormData(this);
                const response = await fetch(this.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                if (response.redirected) {
                    window.location.href = response.url;
                } else {
                    const result = await response.json();
                    if (response.ok) {
                        window.location.reload();
                    } else {
                        alert(result.message || 'Registration failed');
                    }
                }
            } catch (error) {
                console.error('Error:', error);
                alert('An error occurred during registration');
            }
        });

        // Close modal when clicking outside
        window.addEventListener('click', function(event) {
            if (event.target.classList.contains('modal')) {
                document.querySelectorAll('.modal').forEach(modal => {
                    modal.classList.remove('show');
                });
                document.body.style.overflow = 'auto';
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                document.querySelectorAll('.modal.show').forEach(modal => {
                    modal.classList.remove('show');
                });
                document.body.style.overflow = 'auto';
            }
        });
    </script>
</body>
</html>