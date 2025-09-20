<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <title>{{ config('app.name') }}</title>
    <!-- EaseFlow logo-->

    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('easeprint-logo-16x16.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('easeprint-logo-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="180x180" href="{{ asset('easeprint-logo-180x180.png') }}">
    <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('easeprint-logo-192x192.png') }}">
    <link rel="icon" type="image/png" sizes="512x512" href="{{ asset('easeprint-logo-512x512.png') }}">

    <!-- Optional Apple icon -->
    <link rel="apple-touch-icon" href="{{ asset('easeprint-logo-180x180.png') }}">



    
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Poppins Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">

<!-- Swiper CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.css" />

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

        

        .swiper {
    padding-bottom: 40px;
}

html {
  scroll-behavior: smooth;
}

.swiper-pagination-bullet {
    background-color: #B2183A !important;
    opacity: 0.7;
}
.swiper-pagination-bullet-active {
    background-color: #ED4A69 !important;
    opacity: 1;
}

.custom-nav {
    color: #3F1A2B !important;
    background-color: rgba(251, 243, 247, 0.8);
    padding: 10px;
    border-radius: 50%;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    transition: 0.3s;
}
.custom-nav:hover {
    background-color: #FBB3C8 !important;
    color: #B2183A !important;
}
        html::-webkit-scrollbar,
        body::-webkit-scrollbar {
          display: none; 
        }

        body {
          overflow-y: scroll;
          scrollbar-width: none; /* Firefox */
          -ms-overflow-style: none; /* IE 10+ */
          font-family: 'Poppins', sans-serif;
        }

        .fade-in-section {
          opacity: 0;
          transform: translateY(20px);
          transition: opacity 0.8s ease, transform 0.8s ease;
          will-change: opacity, transform;
        }

        .fade-in-section.is-visible {
          opacity: 1;
          transform: translateY(0);
        }
        
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
    opacity: 0;
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
    width: 90%;
    max-width: 28rem;
    padding: 1.5rem;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    transform: translateY(-20px);
    transition: transform 0.3s ease;
}

.modal.show .modal-content {
    transform: translateY(0);
}

        /* Dropdown menu styles */
        .dropdown-menu {
            transition: all 0.2s ease;
            opacity: 0;
            transform: translateY(-10px);
            pointer-events: none;
        }

        .dropdown:hover .dropdown-menu,
        .dropdown-menu:not(.hidden) {
            opacity: 1;
            transform: translateY(0);
            pointer-events: auto;
        }

        /* Logout button styles */
        .logout-btn {
            background: none;
            border: none;
            cursor: pointer;
            width: 100%;
            text-align: left;
            padding: 0.5rem 1rem;
            color: var(--primary);
        }

        .logout-btn:hover {
            background-color: var(--light);
        }

        /* Mobile menu animation */
        .mobile-menu {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease-out;
        }

        .mobile-menu.open {
            max-height: 500px;
            transition: max-height 0.3s ease-in;
        }
    </style>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="bg-[#FBF8FB] text-[#2C2C2C]">
    <!-- Header -->
   <header class="bg-[#1A1A1A] text-white shadow-lg sticky top-0 z-40">
    <div class="container mx-auto px-4 py-3 flex items-center">
        <!-- Left Logo -->
        <div class="flex items-center space-x-2">
            <img src="/image/easeP.jpg" alt="Ease Print Logo" class="w-8 h-8">
            <span class="text-xl font-bold">Ease Print.</span>
        </div>

        <!-- Center Navigation -->
        <div class="flex-1 hidden md:flex justify-center">
            <nav class="flex space-x-6">
                <a href="{{ route('home') }}" class="hover:text-accent transition">Home</a>
                
                  <a href="{{ route('products.index') }}" class="hover:text-accent transition flex items-center">
                    Shop
                </a>



                <a href="{{ route('about') }}" class="hover:text-accent transition">About</a>

                <a href="#footer" class="hover:text-accent transition">Contact</a>
                
                @auth
                <a href="{{ route('chat') }}" class="relative hover:text-accent transition">
                    Chat Us
                    <span id="chat-notif-badge"
                        class="hidden absolute -top-1 -right-4 bg-red-500 text-white text-xs font-bold rounded-full px-1.5 py-0.5">
                        0
                    </span>
                </a>


                @else
                <a href="javascript:void(0)" onclick="openModal('login-modal')" class="hover:text-accent transition">Chat Us</a>
                @endauth
            
              @auth
                <a href="{{ route('custom-shirt.my-requests') }}" class="relative hover:text-accent transition">
                    My Requests
                    <span id="requests-notif-badge"
                        class="hidden absolute -top-1 -right-4 bg-red-500 text-white text-xs font-bold rounded-full px-1.5 py-0.5">
                        0
                    </span>
                </a>
                @else
                <a href="javascript:void(0)" onclick="openModal('login-modal')" class="hover:text-accent transition">
                    My Requests
                </a>
                @endauth

            </nav>
        </div>

        <!-- Right Side Buttons -->
        <div class="hidden md:flex items-center space-x-4">
            @auth
                <!-- Cart -->
                <div class="relative">
                    <a href="{{ route('cart.index') }}" class="hover:text-accent transition">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                        </svg>
                    </a>
                    @if ($cartItemCount > 0)
                        <span class="cart-badge absolute bg-accent text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
                            {{ $cartItemCount }}
                        </span>
                    @endif
                </div>

                <!-- User Dropdown -->
                <div class="dropdown relative">
                    <button class="flex items-center space-x-1 hover:text-accent transition">
                        <span>{{ Auth::user()->name }}</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div class="dropdown-menu absolute right-0 mt-2 hidden bg-white text-primary py-2 shadow-lg rounded-md min-w-[160px]">
                        <a href="{{ route('dashboard') }}" class="block px-4 py-2 hover:bg-light">Dashboard</a>
                        <a href="{{ route('profile.edit') }}" class="block px-4 py-2 hover:bg-light">Profile</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="block w-full text-left px-4 py-2 hover:bg-light">Logout</button>
                        </form>
                    </div>
                </div>
            @else
                <!-- Login/Register Buttons -->
                <button onclick="openModal('login-modal')" class="bg-secondary hover:bg-accent px-4 py-2 rounded-md transition">Login</button>
                <button onclick="openModal('register-modal')" class="bg-secondary hover:bg-accent px-4 py-2 rounded-md transition">Register</button>
            @endauth
        </div>

        <!-- Mobile Menu Button -->
        <button id="mobile-menu-button" class="md:hidden text-white focus:outline-none ml-auto">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
        </button>
    </div>

    <!-- Mobile Navigation -->
    <div id="mobile-menu" class="mobile-menu md:hidden bg-[#1A1A1A] overflow-hidden transition-all duration-300 ease-in-out max-h-0 opacity-0">

        <div class="container mx-auto px-4 py-2">
            <div class="flex flex-col space-y-4">
                <a href="{{ route('home') }}" class="block py-2 hover:text-accent transition">Home</a>

               <a href="{{ route('products.index') }}" class="hover:text-accent transition flex items-center">
                    Shop
                </a>
                <a href="{{ route('about') }}" class="hover:text-accent transition">About</a>


                <a href="#footer" class="block py-2 hover:text-accent transition">Contact</a>

                @auth
                    <a href="{{ route('chat') }}" class="block py-2 hover:text-accent transition">Chat Us</a>
                @else
                    <a href="javascript:void(0)" onclick="openModal('login-modal')" class="block py-2 hover:text-accent transition">Chat Us</a>
                @endauth

                  @auth
                <a href="{{ route('custom-shirt.my-requests') }}" class="relative hover:text-accent transition">
                    My Requests
                    <span id="requests-notif-badge"
                        class="hidden absolute -top-1 -right-4 bg-red-500 text-white text-xs font-bold rounded-full px-1.5 py-0.5">
                        0
                    </span>
                </a>
                @else
                <a href="javascript:void(0)" onclick="openModal('login-modal')" class="hover:text-accent transition">
                    My Requests
                </a>
                @endauth

                @auth
                    <div class="pt-2 border-t border-gray-700">
                        <a href="{{ route('cart.index') }}" class="flex items-center py-2 hover:text-accent transition">
                            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                            </svg>
                            Cart <span class="ml-2 bg-accent text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">3</span>
                        </a>
                        <a href="{{ route('dashboard') }}" class="block py-2 hover:text-accent transition">Dashboard</a>
                        <a href="{{ route('profile.edit') }}" class="block py-2 hover:text-accent transition">Profile</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-left py-2 hover:text-accent transition">Logout</button>
                        </form>
                    </div>
                @else
                    <div class="pt-2 border-t border-gray-700 flex flex-col space-y-2">
                        <button onclick="openModal('login-modal')" class="w-full text-left py-2 hover:text-accent transition">Login</button>
                        <button onclick="openModal('register-modal')" class="w-full text-left py-2 hover:text-accent transition">Register</button>
                    </div>
                @endauth
            </div>
        </div>
    </div>
</header>


@unless(isset($hideHero) && $hideHero)
<!-- Hero Section -->
   <section class="relative w-full h-[200px] sm:h-[300px] md:h-[400px] lg:h-[550px] bg-cover overflow-hidden">
  <!-- Dynamic Background Images -->
  <div class="absolute inset-0 bg-black opacity-70 z-0"></div>
  <div class="absolute inset-0 bg-cover bg-center transition-opacity duration-1000 opacity-0" 
       style="background-image: url('{{ asset('image/EP.jpg') }}');" data-active></div>
  <div class="absolute inset-0 bg-cover bg-center transition-opacity duration-1000 opacity-0" 
       style="background-image: url('{{ asset('image/Ease1.jpg') }}');"></div>
  <div class="absolute inset-0 bg-cover bg-center transition-opacity duration-1000 opacity-0" 
       style="background-image: url('{{ asset('image/Ease2.jpg') }}');"></div>

  <!-- Hero Content (unchanged) -->
  <div class="relative z-10 flex flex-col items-center justify-center h-full text-[#FBF8FB] text-center px-4">
    <h1 class="text-4xl sm:text-5xl md:text-6xl lg:text-[64px] font-bold mb-2 sm:mb-4">Welcome to EaseFlow</h1>
    <p class="text-xl sm:text-2xl md:text-3xl lg:text-[32px] font-normal">Print Your Vision with Ease</p>
    <a href="#product"
       class="mt-4 sm:mt-6 md:mt-8 bg-[#B2183A] text-[#FBF8FB] text-base sm:text-lg md:text-[20px] px-4 py-2 sm:px-5 sm:py-2.5 md:px-6 md:py-3 rounded hover:bg-[#3F1A2B] hover:scale-110 transition-all duration-200">
      Shop Now
    </a>
  </div>
</section>

@endunless

    <!-- Main Content -->
    <main id="product" class="container mx-auto px-4 py-8 sm:py-12">
        @yield('contents')
    </main>

@unless(isset($hideHero) && $hideHero)
    <!-- REQUEST PERSONAL STYLE SECTION -->
    <section class="pt-12 sm:pt-16 md:pt-[100px] pb-12 sm:pb-16 md:pb-[80px]">
        <h2 class="text-3xl sm:text-4xl md:text-[40px] font-bold text-center mb-6 sm:mb-8 md:mb-10">REQUEST PERSONAL STYLE</h2>

        <div class="relative">
            <img src="/image/Easeprint.jpg" 
                alt="Request Style Background" 
                class="w-full h-[300px] sm:h-[400px] md:h-[500px] object-cover object-[50%_10%]">

            <div class="absolute inset-0 bg-black bg-opacity-50 flex flex-col md:flex-row items-center justify-between text-white p-6 sm:p-8 md:p-10 lg:px-[150px]">
                <div class="mb-4 md:mb-0">
                    <h3 class="text-3xl sm:text-4xl md:text-5xl lg:text-[64px] font-bold">Make your own style</h3>
                    <p class="text-xl sm:text-2xl md:text-3xl lg:text-[32px] font-normal">Let's ink your imagination</p>
                </div>
              @auth
                <a href="{{ route('custom-shirt.create') }}"
                    class="mt-4 sm:mt-6 md:mt-8 lg:mt-16 bg-[#B2183A] text-[#FBF8FB] text-base sm:text-lg md:text-[20px] px-4 py-2 sm:px-5 sm:py-2.5 md:px-6 md:py-3 rounded hover:bg-[#3F1A2B] hover:scale-110 transition-all duration-200">
                    REQUEST APPOINTMENT
                </a>
            @else
                <a href="javascript:void(0)" onclick="openModal('login-modal')"
                    class="mt-4 sm:mt-6 md:mt-8 lg:mt-16 bg-[#B2183A] text-[#FBF8FB] text-base sm:text-lg md:text-[20px] px-4 py-2 sm:px-5 sm:py-2.5 md:px-6 md:py-3 rounded hover:bg-[#3F1A2B] hover:scale-110 transition-all duration-200">
                    REQUEST APPOINTMENT
                </a>
            @endauth

            </div>
        </div>


        <!-- DESCRIPTION & GUIDE -->
        <div class="max-w-5xl mx-auto mt-8 sm:mt-10 md:mt-12 text-center px-4 sm:px-6">
            <h3 class="text-xl sm:text-2xl font-semibold mb-3 sm:mb-4 text-[#1A1A1A]">Design On Your Terms</h3>
            <p class="text-sm sm:text-[16px] text-[#4A4A4A] leading-relaxed">
                The Request Personal Style lets you upload your own jersey, shirt, or shorts design — or simply describe your creative idea! 
                Ease Print will work with your style and bring it to life using high-quality printing services tailored to your vision.
            </p>
            <p class="text-sm sm:text-[16px] text-[#4A4A4A] leading-relaxed mt-3 sm:mt-4">
                Whether you're submitting a complete design or just a sketch, this feature is made for customers who want something unique, personal, and truly custom. 
                Just hit the "Request Appointment" button and let's create something one-of-a-kind together.
            </p>
        </div>
    </section>


    <!-- Footer -->
    <footer id="footer" class="bg-[#1A1A1A] text-[#FBF8FB] py-8 sm:py-10 md:py-12 px-4 sm:px-6 md:px-8 lg:px-20">
        <div class="max-w-7xl mx-auto grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 sm:gap-8">
            <!-- Logo / About -->
            <div>
                <h2 class="text-xl sm:text-2xl font-bold mb-3 sm:mb-4">Ease Print</h2>
                <p class="text-xs sm:text-sm leading-relaxed">
                    Bringing your custom print ideas to life with quality, creativity, and convenience.
                </p>
            </div>

            <!-- Quick Links -->
            <div>
                <h3 class="text-lg sm:text-xl font-semibold mb-3 sm:mb-4">Quick Links</h3>
                <ul class="space-y-1 sm:space-y-2 text-xs sm:text-sm">
                    <li><a href="#" class="hover:underline">Home</a></li>
                    <li><a href="#" class="hover:underline">Shop</a></li>
                    <li><a href="#" class="hover:underline">Request Style</a></li>
                    <li><a href="#" class="hover:underline">Contact</a></li>
                </ul>
            </div>

            <!-- Services -->
            <div>
                <h3 class="text-lg sm:text-xl font-semibold mb-3 sm:mb-4">Services</h3>
                <ul class="space-y-1 sm:space-y-2 text-xs sm:text-sm">
                    <li><a href="#" class="hover:underline">Custom Jerseys</a></li>
                    <li><a href="#" class="hover:underline">T-Shirt Printing</a></li>
                    <li><a href="#" class="hover:underline">Shorts Design</a></li>
                    <li><a href="#" class="hover:underline">Bulk Orders</a></li>
                </ul>
            </div>

            <!-- Contact -->
            <div>
                <h3 class="text-lg sm:text-xl font-semibold mb-3 sm:mb-4">Contact Us</h3>
                <p class="text-xs sm:text-sm">Email: support@easeprint.com</p>
                <p class="text-xs sm:text-sm">Phone: +63 912 345 6789</p>
                <p class="text-xs sm:text-sm">Address: Brgy. Printing, City, PH</p>
            </div>
        </div>

        <div class="border-t border-gray-700 mt-8 sm:mt-10 md:mt-12 pt-4 sm:pt-5 md:pt-6 text-center text-xs sm:text-sm text-gray-400">
            &copy; 2025 Ease Print. All rights reserved.
        </div>
    </footer>
@endunless

<!-- Login Modal -->
<div id="login-modal" class="modal">
    <div class="modal-content p-6 sm:p-8 rounded-xl shadow-lg bg-white">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-primary">Welcome Back!</h2>
            <button onclick="closeModal('login-modal')" class="text-accent hover:text-secondary text-2xl font-bold">
                &times;
            </button>
        </div>

        <!-- Form -->
        <form id="login-form" action="{{ route('login') }}" method="POST" class="space-y-5">
            @csrf

            <!-- Email -->
            <div>
                <label for="login-email" class="block text-sm font-medium text-primary mb-1">Email</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-accent">
                        <i class="fas fa-envelope"></i>
                    </span>
                    <input 
                        type="email" 
                        name="email" 
                        id="login-email" 
                        class="w-full border rounded-lg pl-10 pr-3 py-2 text-sm focus:ring-2 focus:ring-accent focus:border-accent" 
                        placeholder="Enter your email"
                        required
                    >
                </div>
            </div>

            <!-- Password -->
            <div>
                <label for="login-password" class="block text-sm font-medium text-primary mb-1">Password</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-accent">
                        <i class="fas fa-lock"></i>
                    </span>
                    <input 
                        type="password" 
                        name="password" 
                        id="login-password" 
                        class="w-full border rounded-lg pl-10 pr-3 py-2 text-sm focus:ring-2 focus:ring-accent focus:border-accent" 
                        placeholder="Enter your password"
                        required
                    >
                </div>
            </div>

            <!-- Login Button -->
            <button 
                type="submit" 
                class="w-full bg-secondary hover:bg-accent text-white py-3 rounded-lg font-bold text-base transition">
                Login
            </button>

            <!-- Social Login -->
            <div class="flex flex-col gap-3">
                <a href="{{ route('auth.google.redirect') }}" 
                   class="flex items-center justify-center border border-gray-300 rounded-lg py-3 text-sm font-medium text-primary hover:bg-gray-100 transition">
                    <img src="https://www.svgrepo.com/show/355037/google.svg" alt="Google" class="w-5 h-5 mr-2">
                    Continue with Google
                </a>
                <a href="{{ route('auth.facebook.redirect') }}" 
                   class="flex items-center justify-center border border-gray-300 rounded-lg py-3 text-sm font-medium text-primary hover:bg-gray-100 transition">
                    <img src="https://www.svgrepo.com/show/452196/facebook-1.svg" alt="Facebook" class="w-5 h-5 mr-2">
                    Continue with Facebook
                </a>
            </div>

            <!-- Footer -->
            <div class="text-center text-sm text-primary">
                Don't have an account? 
                <a href="#" onclick="closeModal('login-modal'); openModal('register-modal');" class="text-accent hover:underline font-medium">
                    Sign up
                </a>
            </div>
        </form>
    </div>
</div>


<!-- Register Modal -->
<div id="register-modal" class="modal">
    <div class="modal-content p-6 sm:p-8 rounded-xl shadow-lg bg-white">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-primary">Create Account</h2>
            <button onclick="closeModal('register-modal')" class="text-accent hover:text-secondary text-2xl font-bold">
                &times;
            </button>
        </div>

        <!-- Form -->
        <form id="register-form" action="{{ route('register') }}" method="POST" class="space-y-5">
            @csrf

            <!-- Full Name -->
            <div>
                <label for="register-name" class="block text-sm font-medium text-primary mb-1">Full Name</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-accent">
                        <i class="fas fa-user"></i>
                    </span>
                    <input 
                        type="text" 
                        name="name" 
                        id="register-name" 
                        class="w-full border rounded-lg pl-10 pr-3 py-2 text-sm focus:ring-2 focus:ring-accent focus:border-accent" 
                        placeholder="Enter your full name"
                        required
                    >
                </div>
            </div>

            <!-- Email -->
            <div>
                <label for="register-email" class="block text-sm font-medium text-primary mb-1">Email</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-accent">
                        <i class="fas fa-envelope"></i>
                    </span>
                    <input 
                        type="email" 
                        name="email" 
                        id="register-email" 
                        class="w-full border rounded-lg pl-10 pr-3 py-2 text-sm focus:ring-2 focus:ring-accent focus:border-accent" 
                        placeholder="Enter your email"
                        required
                    >
                </div>
            </div>

            <!-- Password -->
            <div>
                <label for="register-password" class="block text-sm font-medium text-primary mb-1">Password</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-accent">
                        <i class="fas fa-lock"></i>
                    </span>
                    <input 
                        type="password" 
                        name="password" 
                        id="register-password" 
                        class="w-full border rounded-lg pl-10 pr-3 py-2 text-sm focus:ring-2 focus:ring-accent focus:border-accent" 
                        placeholder="Enter your password"
                        required
                    >
                </div>
            </div>

            <!-- Confirm Password -->
            <div>
                <label for="register-password-confirm" class="block text-sm font-medium text-primary mb-1">Confirm Password</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-accent">
                        <i class="fas fa-lock"></i>
                    </span>
                    <input 
                        type="password" 
                        name="password_confirmation" 
                        id="register-password-confirm" 
                        class="w-full border rounded-lg pl-10 pr-3 py-2 text-sm focus:ring-2 focus:ring-accent focus:border-accent" 
                        placeholder="Re-enter your password"
                        required
                    >
                </div>
            </div>

            <!-- Register Button -->
            <button 
                type="submit" 
                class="w-full bg-secondary hover:bg-accent text-white py-3 rounded-lg font-bold text-base transition">
                Register
            </button>

            <!-- Divider -->
            <div class="flex items-center my-4">
                <hr class="flex-grow border-gray-300">
                <span class="px-2 text-sm text-gray-500">OR</span>
                <hr class="flex-grow border-gray-300">
            </div>

            <!-- Social Sign-Up -->
            <div class="flex flex-col gap-3">
                <a href="{{ route('auth.google.redirect') }}" 
                   class="flex items-center justify-center border border-gray-300 rounded-lg py-3 text-sm font-medium text-primary bg-white hover:bg-gray-100 transition">
                    <img src="https://www.svgrepo.com/show/355037/google.svg" alt="Google" class="w-5 h-5 mr-2">
                    Sign up with Google
                </a>
                <a href="{{ route('auth.facebook.redirect') }}" 
                   class="flex items-center justify-center rounded-lg py-3 text-sm font-medium text-white bg-[#1877F2] hover:bg-[#145dbf] transition">
                    <img src="https://www.svgrepo.com/show/452196/facebook-1.svg" alt="Facebook" class="w-5 h-5 mr-2">
                    Sign up with Facebook
                </a>
            </div>

            <!-- Footer -->
            <div class="text-center text-sm text-primary">
                Already have an account? 
                <a href="#" onclick="closeModal('register-modal'); openModal('login-modal');" class="text-accent hover:underline font-medium">
                    Login
                </a>
            </div>
        </form>
    </div>
</div>



    <script>
        // Mobile menu toggle
        const mobileMenuButton = document.getElementById("mobile-menu-button");
    const mobileMenu = document.getElementById("mobile-menu");

    mobileMenuButton.addEventListener("click", () => {
        const isOpen = mobileMenu.classList.contains("max-h-[1000px]");

        if (isOpen) {
            mobileMenu.classList.remove("max-h-[1000px]", "opacity-100");
            mobileMenu.classList.add("max-h-0", "opacity-0");
        } else {
            mobileMenu.classList.remove("max-h-0", "opacity-0");
            mobileMenu.classList.add("max-h-[1000px]", "opacity-100");
        }
    });


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

        // Handle dropdown menus
        const dropdowns = document.querySelectorAll('.dropdown');
        
        dropdowns.forEach(dropdown => {
            const button = dropdown.querySelector('button');
            const menu = dropdown.querySelector('.dropdown-menu');
            
            button.addEventListener('click', () => {
                menu.classList.toggle('hidden');
            });
            
            // Close when clicking outside
            document.addEventListener('click', (e) => {
                if (!dropdown.contains(e.target)) {
                    menu.classList.add('hidden');
                }
            });
        });

        // Intersection Observer for fade-in animations
        const fadeInSections = document.querySelectorAll('.fade-in-section');
        
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                }
            });
        }, { threshold: 0.1 });
        
        fadeInSections.forEach(section => {
            observer.observe(section);
        });

      @auth
document.addEventListener('DOMContentLoaded', () => {
    checkChatNotification();
    setInterval(checkChatNotification, 3000);
});

async function checkChatNotification() {
    const userId = {{ Auth::id() }};
    const apiUrl = `http://127.0.0.1:8000/api/messages/${userId}`;

    try {
        const res = await fetch(apiUrl);
        const messages = await res.json();

        const unreadCount = messages.filter(msg => msg.is_admin && !msg.read).length;

        const badge = document.getElementById('chat-notif-badge');
        if (badge) {
            if (unreadCount > 0) {
                badge.textContent = unreadCount > 99 ? "99+" : unreadCount;
                badge.classList.remove('hidden');
            } else {
                badge.classList.add('hidden');
            }
        }
    } catch (error) {
        console.error('Error fetching messages:', error);
    }
}
@endauth


document.addEventListener('DOMContentLoaded', () => {
    const backgrounds = document.querySelectorAll('[style*="background-image"]');
    let currentIndex = 0;

    function rotateBackground() {
      // Fade out current background
      backgrounds[currentIndex].classList.remove('opacity-100');
      backgrounds[currentIndex].classList.add('opacity-0');

      // Move to next background (loop)
      currentIndex = (currentIndex + 1) % backgrounds.length;

      // Fade in new background
      backgrounds[currentIndex].classList.remove('opacity-0');
      backgrounds[currentIndex].classList.add('opacity-100');
    }

    // Initialize: Show first background
    backgrounds[0].classList.add('opacity-100');

    // Rotate every 3 seconds (adjust timing as needed)
    setInterval(rotateBackground, 3000);
  });

  const heroSection = document.querySelector('section');
let interval = setInterval(rotateBackground, 3000);

heroSection.addEventListener('mouseenter', () => clearInterval(interval));
heroSection.addEventListener('mouseleave', () => {
  interval = setInterval(rotateBackground, 3000);
});


    </script>
</body>
</html>