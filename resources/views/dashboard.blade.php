@extends('layouts.app')

@php
    $hideHero = true;
@endphp

@section('contents')
<!-- AOS CSS -->
<link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">

<div class="bg-[#FBF8FB] py-10">
    <div class="max-w-7xl mx-auto px-6 space-y-12">

        <!-- Dashboard Header -->
        <div data-aos="fade-down" class="flex flex-col sm:flex-row items-center justify-between bg-white rounded-xl shadow-lg p-8 border-t-4 border-[#B2183A]">
            <div>
                <h1 class="text-3xl font-bold text-[#3F1A2B]">Welcome, {{ Auth::user()->name }}!</h1>
                <p class="mt-2 text-gray-600 text-sm">Your EASEPrint Dashboard</p>
            </div>
            <div class="rounded-full border border-gray-200 p-4 shadow-sm mt-6 sm:mt-0">
                <i class="fas fa-user-circle text-5xl text-[#ED4A69]"></i>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-8">
            <div data-aos="zoom-in" data-aos-delay="100" class="bg-white rounded-xl shadow-lg p-6 flex items-center border-t-4 border-[#B2183A] hover:shadow-2xl transition">
                <div class="bg-[#B2183A] text-white p-4 rounded-full mr-5">
                    <i class="fas fa-box-open text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Orders</p>
                    <p class="text-2xl font-bold text-[#3F1A2B]">12</p>
                </div>
            </div>
            <div data-aos="zoom-in" data-aos-delay="200" class="bg-white rounded-xl shadow-lg p-6 flex items-center border-t-4 border-[#ED4A69] hover:shadow-2xl transition">
                <div class="bg-[#ED4A69] text-white p-4 rounded-full mr-5">
                    <i class="fas fa-shopping-cart text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Cart Items</p>
                    <p class="text-2xl font-bold text-[#3F1A2B]">3</p>
                </div>
            </div>
            <div data-aos="zoom-in" data-aos-delay="300" class="bg-white rounded-xl shadow-lg p-6 flex items-center border-t-4 border-[#3F1A2B] hover:shadow-2xl transition">
                <div class="bg-[#3F1A2B] text-white p-4 rounded-full mr-5">
                    <i class="fas fa-star text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Favorites</p>
                    <p class="text-2xl font-bold text-[#3F1A2B]">5</p>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div data-aos="fade-up">
            <h2 class="text-xl font-bold text-[#3F1A2B] mb-6">Recent Activity</h2>
            <div class="bg-white rounded-xl shadow-lg divide-y divide-gray-100 border-t-4 border-[#ED4A69]">
                <div data-aos="fade-left" data-aos-delay="100" class="flex items-center p-5 hover:bg-gray-50 transition">
                    <div class="bg-[#B2183A] text-white p-3 rounded-full mr-4">
                        <i class="fas fa-shipping-fast text-sm"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-[#3F1A2B]">Order #12345 shipped</p>
                        <p class="text-xs text-gray-500">Yesterday at 2:45 PM</p>
                    </div>
                </div>
                <div data-aos="fade-left" data-aos-delay="200" class="flex items-center p-5 hover:bg-gray-50 transition">
                    <div class="bg-[#ED4A69] text-white p-3 rounded-full mr-4">
                        <i class="fas fa-shopping-cart text-sm"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-[#3F1A2B]">Added new item to cart</p>
                        <p class="text-xs text-gray-500">2 days ago</p>
                    </div>
                </div>
                <div data-aos="fade-left" data-aos-delay="300" class="flex items-center p-5 hover:bg-gray-50 transition">
                    <div class="bg-[#3F1A2B] text-white p-3 rounded-full mr-4">
                        <i class="fas fa-user-plus text-sm"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-[#3F1A2B]">Account created</p>
                        <p class="text-xs text-gray-500">1 week ago</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div data-aos="fade-up">
            <h2 class="text-xl font-bold text-[#3F1A2B] mb-6">Quick Actions</h2>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-8">
                <a data-aos="zoom-in" data-aos-delay="100" href="{{ route('products.index') }}" class="bg-white rounded-xl shadow-lg p-6 text-center hover:shadow-2xl transition border-t-4 border-[#B2183A]">
                    <i class="fas fa-boxes text-2xl text-[#B2183A] mb-2"></i>
                    <p class="text-sm font-semibold text-[#3F1A2B]">Browse Products</p>
                </a>
                <a data-aos="zoom-in" data-aos-delay="200" href="{{ route('orders.index') }}" class="bg-white rounded-xl shadow-lg p-6 text-center hover:shadow-2xl transition border-t-4 border-[#ED4A69]">
                    <i class="fas fa-clipboard-list text-2xl text-[#ED4A69] mb-2"></i>
                    <p class="text-sm font-semibold text-[#3F1A2B]">My Carts</p>
                </a>
                <a data-aos="zoom-in" data-aos-delay="300" href="#" class="bg-white rounded-xl shadow-lg p-6 text-center hover:shadow-2xl transition border-t-4 border-[#3F1A2B]">
                    <i class="fas fa-heart text-2xl text-[#3F1A2B] mb-2"></i>
                    <p class="text-sm font-semibold text-[#3F1A2B]">My Request</p>
                </a>
                <a data-aos="zoom-in" data-aos-delay="400" href="#" class="bg-white rounded-xl shadow-lg p-6 text-center hover:shadow-2xl transition border-t-4 border-[#B2183A]">
                    <i class="fas fa-cog text-2xl text-[#B2183A] mb-2"></i>
                    <p class="text-sm font-semibold text-[#3F1A2B]">Settings</p>
                </a>
            </div>
        </div>

        <!-- Recommended Products -->
        <div data-aos="fade-up">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-bold text-[#3F1A2B]">Recommended For You</h2>
                <a href="{{ route('products.index') }}" class="text-sm text-[#ED4A69] hover:underline">View All</a>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse($products as $index => $product)
                <div data-aos="fade-up" data-aos-delay="{{ ($index % 3) * 100 + 100 }}" class="bg-white rounded-xl shadow-lg hover:shadow-2xl transition border-t-4 border-[#B2183A]">
                    <div class="relative">
                        <img src="{{ Storage::url($product->image ?? 'products/placeholder.jpg') }}" alt="{{ $product->name }}" class="w-full h-48 object-cover rounded-t-xl">
                        <button class="absolute top-3 right-3 border border-gray-200 bg-white rounded-full p-2 text-[#ED4A69] shadow hover:bg-[#ED4A69] hover:text-white transition">
                            <i class="fas fa-heart"></i>
                        </button>
                    </div>
                    <div class="p-5">
                        <h3 class="text-lg font-semibold text-[#3F1A2B]">{{ $product->name }}</h3>
                        <p class="text-sm text-gray-600 mb-3">{{ Str::limit($product->description, 40) }}</p>
                        <div class="flex justify-between items-center">
                            <span class="text-lg font-bold text-[#B2183A]">₱{{ number_format($product->price, 2) }}</span>
                            @auth
                            <form action="{{ route('cart.add', $product) }}" method="POST">
                                @csrf
                                <button type="submit" class="bg-[#ED4A69] text-white px-4 py-2 rounded-full text-sm hover:bg-[#B2183A] transition">
                                    <i class="fas fa-cart-plus mr-1"></i> Add
                                </button>
                            </form>
                            @else
                            <button onclick="openModal('login-modal')" class="bg-[#ED4A69] text-white px-4 py-2 rounded-full text-sm hover:bg-[#B2183A] transition">
                                <i class="fas fa-cart-plus mr-1"></i> Add
                            </button>
                            @endauth
                        </div>
                    </div>
                </div>
                @empty
                <p class="text-gray-500">No products available right now.</p>
                @endforelse
            </div>
        </div>

        <!-- Footer Activity -->
        <div data-aos="fade-up">
            <div class="bg-white rounded-xl shadow-lg p-6 text-center border-t-4 border-[#ED4A69]">
                <h3 class="text-base font-semibold text-[#3F1A2B] border-b border-gray-100 pb-3 mb-4">Account Activity</h3>
                <p class="text-sm text-gray-600 mb-2">
                    <i class="fas fa-sign-in-alt text-[#ED4A69] mr-2"></i>
                    Last login:
                    <span class="font-medium text-[#3F1A2B]">
                        {{ Auth::user()->last_login_at ? Auth::user()->last_login_at->format('M j, Y g:i A') : 'First login' }}
                    </span>
                </p>
                <p class="text-sm text-gray-600">
                    <i class="fas fa-circle {{ Auth::user()->last_seen_at && Auth::user()->last_seen_at->gt(now()->subMinutes(5)) ? 'text-green-500' : 'text-gray-400' }} mr-2"></i>
                    @if(Auth::user()->last_seen_at && Auth::user()->last_seen_at->gt(now()->subMinutes(5)))
                        <span class="font-medium text-green-600">Online now</span>
                    @else
                        Last active:
                        <span class="font-medium text-[#B2183A]">
                            {{ Auth::user()->last_seen_at ? Auth::user()->last_seen_at->diffForHumans() : 'Never' }}
                        </span>
                    @endif
                </p>
            </div>
        </div>

    </div>
</div>

<!-- AOS JS -->
<script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
<script>
    AOS.init({
        duration: 800,
        once: false
    });
</script>
@endsection
