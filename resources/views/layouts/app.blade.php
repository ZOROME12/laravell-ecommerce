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
    <script src="https://code.iconify.design/3/3.1.0/iconify.min.js"></script>


    <!-- === NEW NO-CACHE HEADERS === -->
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <!-- === END NO-CACHE HEADERS === -->

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
            max-height: 500px; /* Adjust as needed */
            transition: max-height 0.3s ease-in;
        }

        /* Base Button */
        .chat-btn {
        background-color: #B2183A;
        color: #fff;
        padding: 0.75rem 1.5rem; /* oblong size */
        border-radius: 9999px;  /* pill/oblong */
        box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
        font-weight: 500;
        cursor: pointer;
        animation: float 3s ease-in-out infinite;
        transition: transform 0.35s ease, background-color 0.35s ease; /* smooth scale + color */
        }

        /* Hover effects */
        .chat-btn:hover {
        background-color: #ED4A69;
        animation: bounce-short 0.4s;
        }

        /* Floating subtle up/down effect */
        @keyframes float {
        0% { transform: translateY(0px); }
        50% { transform: translateY(-4px); }
        100% { transform: translateY(0px); }
        }

        /* Short bounce on hover */
        @keyframes bounce-short {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-6px); }
        }

        [x-cloak] { display: none !important; }
    
        /* App Download Toast */
        #app-download-toast {
            /* Start hidden and off-screen */
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 0.5s ease, transform 0.5s ease;
            visibility: hidden;
        }

        #app-download-toast.show {
            opacity: 1;
            transform: translateY(0);
            visibility: visible;
        }
    </style>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="bg-[#FBF8FB] text-[#2C2C2C]">

    <!-- === STYLISH SPAM ALERT (HIDDEN BY DEFAULT) === -->
    <div id="spamAlert" class="hidden fixed top-5 left-1/2 -translate-x-1/2 bg-red-600 text-white px-6 py-3 rounded-lg shadow-lg z-[1001] transition-all duration-300 opacity-0">
        Action in progress. Please wait.
    </div>

    <!-- Header -->
   <header class="bg-[#1A1A1A] text-white shadow-lg sticky top-0 z-40">
    <div class="container mx-auto px-4 py-3 flex items-center">
        <!-- Left Logo -->
        <div class="flex items-center space-x-2">
            <img src="/image/easeP.jpg" alt="Ease Print Logo" class="w-8 h-8">
            <span class="text-xl font-bold">EasePrint</span>
        </div>

        <!-- Center Navigation -->
        <div class="flex-1 hidden md:flex justify-center">
            <nav class="flex space-x-10"> <!-- increased spacing -->
                <a href="{{ route('home') }}" class="hover:text-accent transition">Home</a>

                <a href="{{ route('shop.index') }}" class="hover:text-accent transition flex items-center">
                    Shop
                </a>

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

                <!-- About moved to last -->
                <a href="{{ route('about') }}" class="hover:text-accent transition">About</a>
            </nav>
        </div>


            @auth
        <!-- Floating Chat Button -->
        <button
            id="chat-toggle"
            class="chat-btn fixed bottom-6 right-6 z-50 flex items-center space-x-2"
        >
            <!-- Chat Icon -->
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                 viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M8 10h.01M12 10h.01M16 10h.01M21 12c0 4.418-4.03 8-9 8-1.045
                         0-2.045-.15-2.97-.428L3 21l1.428-5.03C3.15 14.045
                         3 13.045 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
            </svg>
            <span>EaseChat</span>
        </button>

        <!-- Floating Chat Window -->
        <div id="chat-window"
             class="fixed bottom-20 right-6 w-96 max-w-full bg-white rounded-lg shadow-lg overflow-hidden hidden z-50">
            <!-- Chat Header -->
            <div class="bg-gradient-to-r from-[#3F1A2B] to-[#B2183A] px-4 py-3 flex items-center justify-between">
                <h2 class="text-lg font-bold text-white">EasePrint Chat</h2>
                <button id="chat-close" class="text-white hover:text-gray-300">&times;</button>
            </div>
            <!-- Chat Messages -->
            <div id="chatBox" class="p-4 h-80 overflow-y-auto bg-gray-50 space-y-3"></div>
            <!-- Chat Input -->
            <div class="flex border-t border-gray-200">
                <input
                    type="text"
                    id="messageInput"
                    class="flex-1 px-3 py-2 text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#B2183A]"
                    placeholder="Type your message..."
                />
                <button
                    onclick="sendMessage()"
                    class="bg-[#B2183A] hover:bg-[#ED4A69] text-white px-4 py-2">
                    Send
                </button>
            </div>
        </div>
    @endauth

        <!-- DOWNLOAD APP ADS -->
        <div id="app-download-toast"
            class="fixed bottom-6 left-6 z-50 w-auto max-w-sm p-4 rounded-lg shadow-xl bg-[#3F1A2B] text-white">

            <div class="flex items-start space-x-3">
                <div class="flex-shrink-0 pt-1">
                    <svg class="w-6 h-6 text-light" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                    </svg>
                </div>
                
                <div class="flex-1">
                    <p class="font-semibold">Get the EasePrint App</p>
                    <p class="text-sm text-gray-300">Manage orders and track history on the go.</p>
                    <a href="{{ route('download') }}" 
                    class="inline-block mt-2 text-sm font-bold text-accent hover:underline">
                    Download Now
                    </a>
                </div>
                
                <button id="close-app-toast" class="text-gray-400 hover:text-white transition-colors">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </button>
            </div>
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

            <!-- User Dropdown with Notifications -->
            <div
                x-data="{
                    open: false,
                    notifOpen: false,
                    notifications: [],
                    async fetchNotifications() {
                        try {
                            const res = await fetch('/notifications');
                            const data = await res.json();
                            this.notifications = data;
                        } catch (e) {
                            console.error('Failed to fetch notifications', e);
                        }
                    },
                    async markAsRead(id) {
                        try {
                            await fetch(`/notifications/${id}/read`, {
                                method: 'PUT',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                }
                            });
                            this.notifications = this.notifications.map(n => n.id === id ? {...n, is_read: true} : n);
                        } catch (e) {
                            console.error('Failed to mark notification as read', e);
                        }
                    }
                }"
                x-init="fetchNotifications()"
                class="relative flex items-center space-x-4"
            >
                <div class="relative">
<button @click="notifOpen = !notifOpen; open = false" class="relative focus:outline-none flex items-center">
    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
    </svg>

    <!-- NOTIFICATION BADGE (aligned with cart) -->
    <span
        x-show="notifications.filter(n => !n.is_read).length > 0"
        x-text="notifications.filter(n => !n.is_read).length"
        class="absolute -top-2 right-[-6px] bg-[#ED4A69] text-white text-xs rounded-full h-5 w-5 flex items-center justify-center"
    ></span>
</button>

                    <!-- Notification Dropdown -->
                    <div
                        x-cloak
                        x-show="notifOpen"
                        @click.outside="notifOpen = false"
                        x-transition
                        class="absolute right-0 mt-2 w-72 bg-white text-primary shadow-lg rounded-md z-50 origin-top-right max-h-80 overflow-y-auto"
                    >
                        <template x-if="notifications.length === 0">
                            <div class="p-4 text-center text-gray-500 text-sm">
                                No notifications yet.
                            </div>
                        </template>

                        <template x-for="notif in notifications" :key="notif.id">
                            <div
                                @click="markAsRead(notif.id)"
                                class="px-4 py-2 border-b hover:bg-gray-100 cursor-pointer"
                                :class="notif.is_read ? 'text-gray-500' : 'font-semibold text-black'"
                            >
                                <span x-text="notif.message"></span>
                                <div class="text-xs text-gray-400" x-text="new Date(notif.created_at).toLocaleString()"></div>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- 👤 User Dropdown -->
                <div x-data>
                    <button
                        @click="open = !open; notifOpen = false"
                        class="flex items-center space-x-1 hover:text-accent transition focus:outline-none"
                    >
                        <span>{{ Auth::user()->name }}</span>
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>

                    <div
                        x-cloak
                        x-show="open"
                        @click.outside="open = false"
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 scale-95"
                        x-transition:enter-end="opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-75"
                        x-transition:leave-start="opacity-100 scale-100"
                        x-transition:leave-end="opacity-0 scale-95"
                        class="absolute right-0 mt-2 bg-white text-primary py-2 shadow-lg rounded-md min-w-[160px] z-50 origin-top-right"
                    >
                        <a href="{{ route('dashboard') }}" class="block px-4 py-2 hover:bg-light">Dashboard</a>
                        <a href="{{ route('download') }}" class="block px-4 py-2 hover:bg-light">Download</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="block w-full text-left px-4 py-2 hover:bg-light">Logout</button>
                        </form>
                    </div>
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
    <div id="mobile-menu" class="mobile-menu md:hidden bg-[#1A1A1A] text-white overflow-hidden transition-all duration-300 ease-in-out max-h-0 opacity-0">

        <div class="container mx-auto px-4 py-2">
            <div class="flex flex-col space-y-4">
                <a href="{{ route('home') }}" class="block py-2 hover:text-accent transition">Home</a>

                <a href="{{ route('shop.index') }}" class="hover:text-accent transition flex items-center">
                    Shop
                </a>

                <a href="{{ route('about') }}" class="hover:text-accent transition">About</a>

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
                <li><p class="text-xs sm:text-sm">Email: easeprintcyi@gmail.com</p></li>
               <li><p class="text-xs sm:text-sm">Phone: +63 909 505 6487</p></li>
                <li><p class="text-xs sm:text-sm">Address: Blk 9 lot 41 Phase 1B kasiglahan village brgy san jose, Rizal, Rodriguez (Montalban), 1860, PH</p></li>
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
        <!-- === UPDATED: Added ID 'loginModalForm' and button ID 'modalLoginButton' === -->
        <form id="loginModalForm" action="{{ route('login') }}" method="POST" class="space-y-5">
            @csrf

            <!-- Email -->
            <div>
                <label for="modal-login-email" class="block text-sm text-primary mb-1">Email</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-accent">
                        <i class="fas fa-envelope"></i>
                    </span>
                    <input
                        type="email"
                        name="email"
                        id="modal-login-email"
                        class="w-full border rounded-lg pl-10 pr-3 py-2 text-sm focus:ring-2 focus:ring-accent focus:border-accent"
                        placeholder="Enter your email"
                        required
                        autocomplete="off"
                    >
                </div>
            </div>

            <!-- Password -->
            <div>
                <label for="modal-login-password" class="block text-sm text-primary mb-1">Password</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-accent">
                        <i class="fas fa-lock"></i>
                    </span>
                    <input
                        type="password"
                        name="password"
                        id="modal-login-password"
                        class="w-full border rounded-lg pl-10 pr-3 py-2 text-sm focus:ring-2 focus:ring-accent focus:border-accent"
                        placeholder="Enter your password"
                        required
                        autocomplete="off"
                    >
                </div>
                   <!-- Forgot Password -->
                @if (Route::has('password.request'))
                    <a
                        href="{{ route('password.request') }}"
                        class="text-sm text-accent hover:underline mt-2 inline-block"
                    >
                        Forgot your password?
                    </a>
                @endif
            </div>

            <!-- Submit -->
            <button type="submit" id="modalLoginButton" class="bg-secondary hover:bg-accent text-white w-full py-2 sm:py-3 rounded-lg text-sm sm:text-base mb-3 sm:mb-4 transition">
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
               class="flex items-center justify-center gap-3 w-full rounded-lg py-2 sm:py-3 text-sm sm:text-base text-gray-700 bg-white border border-gray-300 shadow-sm hover:shadow-md hover:bg-gray-50 transition">
                <img src="https://www.gstatic.com/firebasejs/ui/2.0.0/images/auth/google.svg"
                     alt="Google Logo" class="w-5 h-5">
                <span>Continue with Google</span>
            </a>

            <!-- Register Link -->
            <div class="text-center text-xs sm:text-sm text-primary mt-4">
                Don't have an account?
                <a href="#" onclick="closeModal('login-modal'); openModal('register-modal');" class="text-accent hover:underline">Sign up</a>
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
        <!-- === UPDATED: Added ID 'registerModalForm' and button ID 'modalRegisterButton' === -->
        <form id="registerModalForm" action="{{ route('register') }}" method="POST" class="space-y-5">
            @csrf

            <!-- Full Name -->
            <div>
                <label for="modal-register-name" class="block text-sm text-primary mb-1">Full Name</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-accent">
                        <i class="fas fa-user"></i>
                    </span>
                    <input
                        type="text"
                        name="name"
                        id="modal-register-name"
                        class="w-full border rounded-lg pl-10 pr-3 py-2 text-sm focus:ring-2 focus:ring-accent focus:border-accent"
                        placeholder="Enter your full name"
                        required
                    >
                </div>
            </div>

            <!-- Email -->
            <div>
                <label for="modal-register-email" class="block text-sm text-primary mb-1">Email</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-accent">
                        <i class="fas fa-envelope"></i>
                    </span>
                    <input
                        type="email"
                        name="email"
                        id="modal-register-email"
                        class="w-full border rounded-lg pl-10 pr-3 py-2 text-sm focus:ring-2 focus:ring-accent focus:border-accent"
                        placeholder="Enter your email"
                        required
                    >
                </div>
            </div>

            <!-- Password -->
            <!-- === UPDATED: Added relative class for popover positioning === -->
            <div class="relative">
                <label for="modal-register-password" class="block text-sm text-primary mb-1">Password</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-accent">
                        <i class="fas fa-lock"></i>
                    </span>
                    <input
                        type="password"
                        name="password"
                        id="modal-register-password"
                        class="w-full border rounded-lg pl-10 pr-3 py-2 text-sm focus:ring-2 focus:ring-accent focus:border-accent"
                        placeholder="Enter your password"
                        required
                        pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[\W_]).{8,}$"
                        title="Must be 8+ characters, with at least 1 uppercase, 1 lowercase, 1 number, and 1 symbol."
                    >
                </div>
                 <!-- === NEW: Floating Password Requirements Popover (Modal Version) === -->
                 <div id="modal-password-popover"
                      class="hidden opacity-0 transition-opacity duration-300 absolute z-20 left-full top-1/2 -translate-y-1/2 ml-4 w-72 p-4 bg-white rounded-lg shadow-xl border border-gray-200">
                     <!-- Arrow pointing left -->
                     <div class="absolute -left-2 top-1/2 -translate-y-1/2 w-4 h-4 bg-white border-l border-b border-gray-200 transform rotate-45"></div>

                     <p class="font-semibold text-sm text-[#3F1A2B] mb-3">Password must contain:</p>
                     <ul class="space-y-1.5 text-sm">
                         <li id="modal-req-length" class="flex items-center text-red-500">
                             <i id="modal-req-icon-length" class="fa-solid fa-xmark w-4 h-4 mr-2"></i>
                             <span id="modal-req-text-length">At least 8 characters</span>
                         </li>
                         <li id="modal-req-lower" class="flex items-center text-red-500">
                             <i id="modal-req-icon-lower" class="fa-solid fa-xmark w-4 h-4 mr-2"></i>
                             <span id="modal-req-text-lower">At least 1 lowercase letter (a-z)</span>
                         </li>
                         <li id="modal-req-upper" class="flex items-center text-red-500">
                             <i id="modal-req-icon-upper" class="fa-solid fa-xmark w-4 h-4 mr-2"></i>
                             <span id="modal-req-text-upper">At least 1 uppercase letter (A-Z)</span>
                         </li>
                         <li id="modal-req-number" class="flex items-center text-red-500">
                             <i id="modal-req-icon-number" class="fa-solid fa-xmark w-4 h-4 mr-2"></i>
                             <span id="modal-req-text-number">At least 1 number (0-9)</span>
                         </li>
                         <li id="modal-req-symbol" class="flex items-center text-red-500">
                             <i id="modal-req-icon-symbol" class="fa-solid fa-xmark w-4 h-4 mr-2"></i>
                             <span id="modal-req-text-symbol">At least 1 special character (@, #, $, etc.)</span>
                         </li>
                     </ul>
                 </div>
                 <!-- === END NEW === -->
            </div>

            <!-- Confirm Password -->
            <div>
                <label for="modal-register-password-confirm" class="block text-sm text-primary mb-1">Confirm Password</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-accent">
                        <i class="fas fa-lock"></i>
                    </span>
                    <input
                        type="password"
                        name="password_confirmation"
                        id="modal-register-password-confirm"
                        class="w-full border rounded-lg pl-10 pr-3 py-2 text-sm focus:ring-2 focus:ring-accent focus:border-accent"
                        placeholder="Re-enter your password"
                        required
                    >
                </div>
                <!-- === UPDATED: Password Match Message (Modal Version) === -->
                <div id="modal-confirm-password-message" class="mt-1 text-sm font-medium h-5 flex items-center">
                    <!-- Content injected by JS -->
                </div>
                <!-- === END UPDATED === -->
            </div>

            <!-- Submit -->
            <button type="submit" id="modalRegisterButton" class="bg-secondary hover:bg-accent text-white w-full py-2 sm:py-3 rounded-lg text-sm sm:text-base mb-3 sm:mb-4 transition">
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
               class="flex items-center justify-center gap-3 w-full rounded-lg py-2 sm:py-3 text-sm sm:text-base text-gray-700 bg-white border border-gray-300 shadow-sm hover:shadow-md hover:bg-gray-50 transition">
                <img src="https://www.gstatic.com/firebasejs/ui/2.0.0/images/auth/google.svg"
                     alt="Google Logo" class="w-5 h-5">
                <span>Sign up with Google</span>
            </a>

            <!-- Login Link -->
            <div class="text-center text-xs sm:text-sm text-primary mt-4">
                Already have an account?
                <a href="#" onclick="closeModal('register-modal'); openModal('login-modal');" class="text-accent hover:underline">Login</a>
            </div>
        </form>
    </div>
</div>


    <script>
    // Chat toggle
    document.getElementById('chat-toggle').addEventListener('click', () => {
        document.getElementById('chat-window').classList.toggle('hidden');
    });

    document.getElementById('chat-close').addEventListener('click', () => {
        document.getElementById('chat-window').classList.add('hidden');
    });

    let lastMessageId = null;
    let firstLoad = true;
    let waitTimeout = null; // For wait note timer

    document.addEventListener('DOMContentLoaded', () => {
        if (Notification.permission !== 'granted') {
            Notification.requestPermission();
        }
        fetchMessages();
        setInterval(fetchMessages, 3000);
        markMessagesAsRead();
    });

    const userId = {{ Auth::id() }};
    const adminId = 0;
    const apiUrl = `http://127.0.0.1:8000/api`;

    function formatTimestamp(dateString) {
        const date = new Date(dateString);
        const time = date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
        const day = date.toLocaleDateString([], { year: 'numeric', month: 'short', day: 'numeric' });
        return `${day} at ${time}`;
    }

    async function fetchMessages() {
        const res = await fetch(`${apiUrl}/messages/${userId}`);
        const messages = await res.json();

        const box = document.getElementById('chatBox');
        const isAtBottom = box.scrollTop + box.clientHeight >= box.scrollHeight - 50;

        // Append only new messages (don’t wipe chatBox)
        messages.forEach(msg => {
            if (!document.getElementById(`msg-${msg.id}`)) {
                const isAdmin = msg.is_admin == 1;
                const sender = isAdmin ? 'EasePrint' : 'You';
                const timestamp = formatTimestamp(msg.created_at);

                box.innerHTML += `
                    <div id="msg-${msg.id}" class="flex ${isAdmin ? 'justify-start' : 'justify-end'}">
                        <div class="max-w-xs md:max-w-sm">
                            <div class="text-xs text-gray-400 mb-1">${timestamp}</div>
                            <div class="px-4 py-2 rounded-lg shadow ${isAdmin ? 'bg-white border border-gray-200 text-gray-800' : 'bg-[#B2183A] text-white'}">
                                <strong>${sender}:</strong> ${msg.message}
                            </div>
                        </div>
                    </div>
                `;
            }
        });

        if (isAtBottom) {
            box.scrollTop = box.scrollHeight;
        }

        // Notifications + Reset wait timer if admin replies
        if (messages.length > 0) {
            const latestMsg = messages[messages.length - 1];
            if (latestMsg.id !== lastMessageId) {
                if (!firstLoad && latestMsg.is_admin == 1) {
                    notifyUser(latestMsg.message);
                    resetWaitTimer(); // Stop wait timer when admin replies
                }
                lastMessageId = latestMsg.id;
            }
        }
        firstLoad = false;
    }

    async function sendMessage() {
        const input = document.getElementById('messageInput');
        const msg = input.value.trim();
        if (!msg) return;

        await fetch(`${apiUrl}/messages`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                sender_id: userId,
                receiver_id: adminId,
                message: msg,
                is_admin: false
            })
        });

        input.value = '';
        fetchMessages();

        // Start wait note timer after sending
        startWaitTimer();
    }

    // Wait note logic
    function startWaitTimer() {
        clearTimeout(waitTimeout);
        waitTimeout = setTimeout(() => {
            showWaitNote();
        }, 15000); // 15 seconds (change to 20000 for 20s)
    }

    function resetWaitTimer() {
        clearTimeout(waitTimeout);
        // Notes will stay (stack), not cleared
    }

    function showWaitNote() {
        const box = document.getElementById('chatBox');
        const timestamp = new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });

        box.innerHTML += `
            <div class="flex justify-center">
                <div class="px-4 py-2 rounded-lg bg-gray-200 text-gray-700 text-xs italic shadow mt-2 flex items-center gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500 animate-spin" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v4m0 8v4m8-8h-4M4 12H0m16.95 4.95l-2.83-2.83M6.88 6.88l-2.83-2.83m12.02 0l2.83 2.83M6.88 17.12l2.83 2.83"/>
                    </svg>
                    ${timestamp} - Please wait, EasePrint will reply soon...
                </div>
            </div>
        `;
        box.scrollTop = box.scrollHeight;
    }

    function notifyUser(message) {
        if (Notification.permission === 'granted') {
            new Notification("New message", {
                body: message,
                icon: '/icons/mail.svg'
            });
        }
    }

    async function markMessagesAsRead() {
        try {
            await fetch(`${apiUrl}/messages/mark-as-read/${userId}`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' }
            });
        } catch (error) {
            console.error('Failed to mark messages as read:', error);
        }
    }
    </script>

    <script>
    // Mobile menu toggle
    const mobileMenuButton = document.getElementById("mobile-menu-button");
    const mobileMenu = document.getElementById("mobile-menu");

    mobileMenuButton.addEventListener("click", () => {
        const isOpen = mobileMenu.classList.contains("max-h-[1000px]"); // Use a large max-height

        if (isOpen) {
            mobileMenu.classList.remove("max-h-[1000px]", "opacity-100");
            mobileMenu.classList.add("max-h-0", "opacity-0");
        } else {
            mobileMenu.classList.remove("max-h-0", "opacity-0");
            mobileMenu.classList.add("max-h-[1000px]", "opacity-100"); // Use a large max-height
        }
    });


        // Modal functions
        function openModal(modalId) {
            const modal = document.getElementById(modalId);
            if (modal) {
                 // === NEW: Reset form when modal opens ===
                 const form = modal.querySelector('form');
                 if(form) form.reset();
                 // === END NEW ===
                modal.classList.add('show');
                document.body.style.overflow = 'hidden';
            }
        }

        function closeModal(modalId) {
            const modal = document.getElementById(modalId);
             if (modal) {
                modal.classList.remove('show');
                document.body.style.overflow = 'auto';
                 // === NEW: Reset form when modal closes ===
                 const form = modal.querySelector('form');
                 if(form) form.reset();
                 // Reset password validation UI if it's the register modal
                 if (modalId === 'register-modal') {
                    resetPasswordValidationUI('modal-'); // Pass prefix for modal IDs
                 }
                 // === END NEW ===
            }
        }

        // Close modal when clicking outside
        window.addEventListener('click', function(event) {
            if (event.target.classList.contains('modal')) {
                document.querySelectorAll('.modal').forEach(modal => {
                    closeModal(modal.id); // Use closeModal to ensure reset logic runs
                });
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                document.querySelectorAll('.modal.show').forEach(modal => {
                    closeModal(modal.id); // Use closeModal to ensure reset logic runs
                });
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
    const apiUrl = `http://127.0.0.1:8000/api/messages/${userId}`; // Ensure this URL is correct

    try {
        const res = await fetch(apiUrl);
        const messages = await res.json();

        // Assuming API returns an array of message objects { ..., is_admin: boolean, read: boolean }
        const unreadCount = messages.filter(msg => msg.is_admin && !msg.read).length;

        const badge = document.getElementById('chat-notif-badge'); // Make sure this ID exists in your HTML
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
    const backgrounds = document.querySelectorAll('section [style*="background-image"]');
    let currentIndex = 0;

    function rotateBackground() {
      if (backgrounds.length === 0) return; // Exit if no backgrounds found
      // Fade out current background
      backgrounds[currentIndex].classList.remove('opacity-100');
      backgrounds[currentIndex].classList.add('opacity-0');

      // Move to next background (loop)
      currentIndex = (currentIndex + 1) % backgrounds.length;

      // Fade in new background
      backgrounds[currentIndex].classList.remove('opacity-0');
      backgrounds[currentIndex].classList.add('opacity-100');
    }

    // Initialize: Show first background if available
    if (backgrounds.length > 0) {
        backgrounds[0].classList.add('opacity-100');
    }

    // Rotate every 3 seconds (adjust timing as needed)
    let interval = setInterval(rotateBackground, 3000);

    // Pause on hover (optional)
    const heroSection = document.querySelector('section'); // Adjust selector if needed
    if (heroSection) {
        heroSection.addEventListener('mouseenter', () => clearInterval(interval));
        heroSection.addEventListener('mouseleave', () => {
            interval = setInterval(rotateBackground, 3000);
        });
    }
  });


// Dropdown toggle
document.querySelectorAll('.dropdown').forEach(dropdown => {
    const button = dropdown.querySelector('button');
    const menu = dropdown.querySelector('.dropdown-menu');

    button.addEventListener('click', (e) => {
        e.stopPropagation(); // prevent closing immediately
        menu.classList.toggle('hidden');
    });
});

// Close dropdown when clicking outside
document.addEventListener('click', () => {
    document.querySelectorAll('.dropdown-menu').forEach(menu => {
        menu.classList.add('hidden');
    });
});

    // LOGIN SECURITY: Prevent back navigation after logout
(function () {
    const statusUrl = "{{ route('auth.status') }}";
    const loginUrl = "{{ route('login') }}"; // Make sure login route exists

    function forceRedirectIfLoggedOut() {
        // Only run on pages that require authentication
        // Add a check here if needed, e.g., check for a specific body class or element
        // if (!document.body.classList.contains('requires-auth')) return;

        fetch(statusUrl, {
            method: 'GET',
            credentials: 'same-origin', // Important for session cookies
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(res => {
            // If the response is 401 Unauthorized, redirect immediately
            if (res.status === 401) {
                window.location.replace(loginUrl);
                return Promise.reject('Unauthorized'); // Stop further processing
            }
            // Check for explicit 'authenticated: false' in JSON for other cases
            return res.json();
        })
        .then(data => {
            if (data && data.authenticated === false) {
                 window.location.replace(loginUrl);
            }
        })
        .catch(error => {
            // Handle network errors or the rejected promise from 401
            if (error !== 'Unauthorized') {
                console.error('Auth status check failed:', error);
                // Optionally redirect even on network error, depending on security needs
                // window.location.replace(loginUrl);
            }
        });
    }

    window.addEventListener('pageshow', function (event) {
        // Check auth status when page is shown, especially from bfcache
         if (event.persisted) { // Only run if page was from bfcache
             forceRedirectIfLoggedOut();
         }
    });

    // It's generally better to rely on server-side middleware for security
    // The client-side checks are more for UX improvement (quick redirect)
    // You might not need the popstate listener if pageshow handles bfcache
    // window.addEventListener('popstate', forceRedirectIfLoggedOut);

})();

// Notifications
async function fetchNotifications() {
    try {
        const response = await fetch('/api/notifications', { // Ensure API route is correct
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        });

        if (!response.ok) throw new Error('Failed to fetch notifications');

        const notifications = await response.json();
        const dropdown = document.getElementById('notificationDropdown'); // Ensure this ID exists
        const bellCount = document.getElementById('notificationCount'); // Ensure this ID exists

        if (!dropdown || !bellCount) return; // Exit if elements not found

        dropdown.innerHTML = ''; // clear old notifications

        if (notifications.length === 0) {
            dropdown.innerHTML = '<li class="px-4 py-2 text-gray-400 text-sm">No new notifications</li>';
            bellCount.style.display = 'none';
            return;
        }

        // Calculate unread count
        const unreadCount = notifications.filter(n => !n.is_read).length;

        // show count
        bellCount.textContent = unreadCount > 9 ? '9+' : unreadCount; // Limit count display if desired
        bellCount.style.display = unreadCount > 0 ? 'inline-flex' : 'none'; // Use inline-flex for badge alignment


        // populate dropdown
        notifications.forEach(n => {
            const li = document.createElement('li');
            li.className = `px-4 py-2 border-b border-gray-100 ${n.is_read ? 'text-gray-500' : 'font-semibold text-gray-800'} hover:bg-gray-50 cursor-pointer text-sm`;
            // Sanitize message content if needed
            li.innerHTML = `
                <div>${n.message}</div>
                <div class="text-xs text-gray-400 mt-1">${new Date(n.created_at).toLocaleString()}</div>
            `;
            li.onclick = () => markNotificationAsRead(n.id, li);
            dropdown.appendChild(li);
        });

    } catch (error) {
        console.error('Notification fetch error:', error);
         // Optionally display an error message in the dropdown
        const dropdown = document.getElementById('notificationDropdown');
        if (dropdown) dropdown.innerHTML = '<li class="px-4 py-2 text-red-500 text-sm">Error loading notifications</li>';
    }
}

async function markNotificationAsRead(id, element) {
    try {
        await fetch(`/notifications/${id}/read`, { // Ensure this route is correct
            method: 'PUT',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });
        element.classList.remove('font-semibold', 'text-gray-800');
        element.classList.add('text-gray-500');
        // Re-fetch to update the count immediately
        fetchNotifications();
    } catch (error) {
        console.error('Error marking notification as read:', error);
    }
}

// Auto-fetch every 10 seconds or when page loads
document.addEventListener('DOMContentLoaded', () => {
    // Check if user is authenticated before fetching/polling
    @auth
        fetchNotifications();
        setInterval(fetchNotifications, 10000); // Poll every 10 seconds
    @endauth
});

    // === Form Reset & Spam Prevention Script (Applied to Modals) ===
    window.addEventListener('pageshow', function (event) {
        // Reset modal forms on back/reload/pageshow
        const loginModalForm = document.getElementById('loginModalForm');
        const registerModalForm = document.getElementById('registerModalForm');
        const modalLoginButton = document.getElementById('modalLoginButton');
        const modalRegisterButton = document.getElementById('modalRegisterButton');

        if (loginModalForm) loginModalForm.reset();
        if (registerModalForm) registerModalForm.reset();

        if (modalLoginButton) {
            modalLoginButton.disabled = false;
            modalLoginButton.innerHTML = 'Login';
        }
        if (modalRegisterButton) {
            modalRegisterButton.disabled = false;
            modalRegisterButton.innerHTML = 'Register';
        }
    });

    document.addEventListener('DOMContentLoaded', function () {
        const modalSpamAlert = document.getElementById('spamAlert'); // Re-use the same alert

        function showModalAlert(message) {
             if (modalSpamAlert) {
                modalSpamAlert.textContent = message;
                modalSpamAlert.classList.remove('hidden');
                modalSpamAlert.classList.remove('opacity-0');
                setTimeout(() => {
                    modalSpamAlert.classList.add('opacity-0');
                    setTimeout(() => modalSpamAlert.classList.add('hidden'), 300);
                }, 3000);
            }
        }

        function setupModalFormSpamPrevention(formId, buttonId, loadingText) {
            const form = document.getElementById(formId);
            const button = document.getElementById(buttonId);
            let clickCount = 0;

            if (form && button) {
                form.addEventListener('submit', function (e) {
                     // === Validation check before submit (Modal Register Form) ===
                     if (formId === 'registerModalForm') {
                        const passInput = document.getElementById('modal-register-password');
                        const confirmInput = document.getElementById('modal-register-password-confirm');
                        const strongPassRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[\W_]).{8,}$/;

                        if (!passInput || !confirmInput) return;

                        const pass = passInput.value;
                        const confirmPass = confirmInput.value;

                        if (pass !== confirmPass) {
                            e.preventDefault();
                            showModalAlert('Passwords do not match. Please correct them.');
                            clickCount = 0; // Reset on validation fail
                            return;
                        }

                        if (!strongPassRegex.test(pass)) {
                            e.preventDefault();
                            showModalAlert('Password does not meet all requirements.');
                            clickCount = 0; // Reset on validation fail
                            return;
                        }
                    }
                    // === END Validation check ===

                    clickCount++;
                    if (clickCount > 1) {
                        e.preventDefault();
                        showModalAlert('Action already in progress. Please wait.');
                        return;
                    }

                    button.disabled = true;
                    button.innerHTML = `<i class="fas fa-spinner fa-spin mr-2"></i> ${loadingText}...`;
                    // Submit happens normally now
                });
            }
        }

        setupModalFormSpamPrevention('loginModalForm', 'modalLoginButton', 'Logging in');
        setupModalFormSpamPrevention('registerModalForm', 'modalRegisterButton', 'Registering');

        // --- Password Strength Live Validation (Modal Version) ---
         const modalRegisterFormElem = document.getElementById('registerModalForm');
         if (modalRegisterFormElem) {
             const modalPasswordInput = document.getElementById('modal-register-password');
             const modalConfirmInput = document.getElementById('modal-register-password-confirm');
             const modalPopover = document.getElementById('modal-password-popover');

             if (!modalPasswordInput || !modalConfirmInput || !modalPopover) return;

             const modalReqs = {
                 length: { li: document.getElementById('modal-req-length'), icon: document.getElementById('modal-req-icon-length') },
                 lower: { li: document.getElementById('modal-req-lower'), icon: document.getElementById('modal-req-icon-lower') },
                 upper: { li: document.getElementById('modal-req-upper'), icon: document.getElementById('modal-req-icon-upper') },
                 number: { li: document.getElementById('modal-req-number'), icon: document.getElementById('modal-req-icon-number') },
                 symbol: { li: document.getElementById('modal-req-symbol'), icon: document.getElementById('modal-req-icon-symbol') }
             };
             const modalConfirmMsg = document.getElementById('modal-confirm-password-message');

             const modalRegex = {
                 lower: /[a-z]/, upper: /[A-Z]/, number: /[0-9]/, symbol: /[\W_]/
             };
             const modalValidIcon = 'fa-solid fa-check';
             const modalInvalidIcon = 'fa-solid fa-xmark';
             const modalValidColor = 'text-green-600';
             const modalInvalidColor = 'text-red-500';

             function updateModalRequirementUI(req, isValid) {
                 req.icon.className = `${isValid ? modalValidIcon : modalInvalidIcon} w-4 h-4 mr-2`;
                 req.li.className = `flex items-center ${isValid ? modalValidColor : modalInvalidColor}`;
             }

             function validateModalPassword() {
                 const pass = modalPasswordInput.value;
                 updateModalRequirementUI(modalReqs.length, pass.length >= 8);
                 updateModalRequirementUI(modalReqs.lower, modalRegex.lower.test(pass));
                 updateModalRequirementUI(modalReqs.upper, modalRegex.upper.test(pass));
                 updateModalRequirementUI(modalReqs.number, modalRegex.number.test(pass));
                 updateModalRequirementUI(modalReqs.symbol, modalRegex.symbol.test(pass));
             }

            function checkModalPasswordMatch() {
                 const pass = modalPasswordInput.value;
                 const confirmPass = modalConfirmInput.value;
                 if (confirmPass.length === 0) {
                     modalConfirmMsg.innerHTML = ''; return;
                 }
                 if (pass === confirmPass) {
                     modalConfirmMsg.innerHTML = `<i class="fa-solid fa-circle-check mr-1 ${modalValidColor}"></i> <span class="${modalValidColor}">Passwords match!</span>`;
                 } else {
                     modalConfirmMsg.innerHTML = `<i class="fa-solid fa-circle-xmark mr-1 ${modalInvalidColor}"></i> <span class="${modalInvalidColor}">Passwords do not match.</span>`;
                 }
             }

            // Reset UI function needed for closeModal
             window.resetPasswordValidationUI = function(prefix = '') { // Added prefix parameter
                const popoverEl = document.getElementById(`${prefix}password-popover`);
                const confirmMsgEl = document.getElementById(`${prefix}confirm-password-message`);
                if(popoverEl) popoverEl.classList.add('hidden', 'opacity-0');
                if(confirmMsgEl) confirmMsgEl.innerHTML = '';
                 // Reset icons and colors
                ['length', 'lower', 'upper', 'number', 'symbol'].forEach(key => {
                    const reqIcon = document.getElementById(`${prefix}req-icon-${key}`);
                    const reqLi = document.getElementById(`${prefix}req-${key}`);
                     if(reqIcon) reqIcon.className = `${modalInvalidIcon} w-4 h-4 mr-2`; // Use modalInvalidIcon
                     if(reqLi) reqLi.className = `flex items-center ${modalInvalidColor}`; // Use modalInvalidColor
                });
             }

             modalPasswordInput.addEventListener('focus', () => {
                 modalPopover.classList.remove('hidden', 'opacity-0');
                 validateModalPassword();
             });
             modalPasswordInput.addEventListener('blur', () => {
                 setTimeout(() => modalPopover.classList.add('hidden', 'opacity-0'), 150);
             });
             modalPasswordInput.addEventListener('keyup', () => {
                 validateModalPassword();
                 checkModalPasswordMatch();
             });
             modalConfirmInput.addEventListener('keyup', checkModalPasswordMatch);
         }

         
         // --- END Modal Password Strength ---
    });


document.addEventListener('DOMContentLoaded', function() {
    const toast = document.getElementById('app-download-toast');
    const closeButton = document.getElementById('close-app-toast');

    if (toast && closeButton) {
        // Show the toast after a 3-second delay
        setTimeout(() => {
            toast.classList.add('show');
        }, 3000);

        // Handle the close button click
        closeButton.addEventListener('click', function() {
            toast.classList.remove('show');
            // It will stay hidden for this page view
        });
    }
});

    </script>


</body>
</html>
