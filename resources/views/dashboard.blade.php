@extends('layouts.app')

@section('contents')
<div class="container mx-auto px-4 py-6 sm:py-8">
    <div class="max-w-4xl mx-auto bg-white rounded-lg shadow-md overflow-hidden">
        <!-- Dashboard Header -->
        <div class="bg-gradient-to-r from-primary to-secondary p-4 sm:p-6 text-white">
            <div class="flex flex-col sm:flex-row justify-between items-center">
                <div class="mb-4 sm:mb-0">
                    <h1 class="text-2xl sm:text-3xl font-bold">Welcome, {{ Auth::user()->name }}!</h1>
                    <p class="mt-1 sm:mt-2 text-sm sm:text-base">Your EASEPrint Dashboard</p>
                </div>
                <div class="bg-white text-primary rounded-full p-2 sm:p-3 shadow-lg">
                    <i class="fas fa-user-circle text-3xl sm:text-4xl"></i>
                </div>
            </div>
        </div>

        <!-- Dashboard Content -->
        <div class="p-4 sm:p-6">
            <!-- Quick Stats -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 mb-6 sm:mb-8">
                <div class="bg-background p-3 sm:p-4 rounded-lg border border-light">
                    <div class="flex items-center">
                        <div class="bg-accent text-white p-2 sm:p-3 rounded-full mr-3 sm:mr-4">
                            <i class="fas fa-box-open text-sm sm:text-base"></i>
                        </div>
                        <div>
                            <p class="text-xs sm:text-sm text-gray-500">Orders</p>
                            <p class="text-lg sm:text-xl font-bold">12</p>
                        </div>
                    </div>
                </div>
                <div class="bg-background p-3 sm:p-4 rounded-lg border border-light">
                    <div class="flex items-center">
                        <div class="bg-secondary text-white p-2 sm:p-3 rounded-full mr-3 sm:mr-4">
                            <i class="fas fa-shopping-cart text-sm sm:text-base"></i>
                        </div>
                        <div>
                            <p class="text-xs sm:text-sm text-gray-500">Cart Items</p>
                            <p class="text-lg sm:text-xl font-bold">3</p>
                        </div>
                    </div>
                </div>
                <div class="bg-background p-3 sm:p-4 rounded-lg border border-light">
                    <div class="flex items-center">
                        <div class="bg-primary text-white p-2 sm:p-3 rounded-full mr-3 sm:mr-4">
                            <i class="fas fa-star text-sm sm:text-base"></i>
                        </div>
                        <div>
                            <p class="text-xs sm:text-sm text-gray-500">Favorites</p>
                            <p class="text-lg sm:text-xl font-bold">5</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="mb-6 sm:mb-8">
                <h2 class="text-lg sm:text-xl font-bold text-primary mb-3 sm:mb-4">Recent Activity</h2>
                <div class="bg-background rounded-lg border border-light overflow-hidden">
                    <ul class="divide-y divide-light">
                        <li class="p-3 sm:p-4 hover:bg-light transition">
                            <div class="flex items-center">
                                <div class="bg-accent text-white p-1.5 sm:p-2 rounded-full mr-2 sm:mr-3">
                                    <i class="fas fa-shipping-fast text-xs sm:text-sm"></i>
                                </div>
                                <div>
                                    <p class="text-sm sm:text-base font-medium">Order #12345 shipped</p>
                                    <p class="text-xs sm:text-sm text-gray-500">Yesterday at 2:45 PM</p>
                                </div>
                            </div>
                        </li>
                        <li class="p-3 sm:p-4 hover:bg-light transition">
                            <div class="flex items-center">
                                <div class="bg-secondary text-white p-1.5 sm:p-2 rounded-full mr-2 sm:mr-3">
                                    <i class="fas fa-shopping-cart text-xs sm:text-sm"></i>
                                </div>
                                <div>
                                    <p class="text-sm sm:text-base font-medium">Added new item to cart</p>
                                    <p class="text-xs sm:text-sm text-gray-500">2 days ago</p>
                                </div>
                            </div>
                        </li>
                        <li class="p-3 sm:p-4 hover:bg-light transition">
                            <div class="flex items-center">
                                <div class="bg-primary text-white p-1.5 sm:p-2 rounded-full mr-2 sm:mr-3">
                                    <i class="fas fa-user-plus text-xs sm:text-sm"></i>
                                </div>
                                <div>
                                    <p class="text-sm sm:text-base font-medium">Account created</p>
                                    <p class="text-xs sm:text-sm text-gray-500">1 week ago</p>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Quick Actions -->
            <div>
                <h2 class="text-lg sm:text-xl font-bold text-primary mb-3 sm:mb-4">Quick Actions</h2>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 sm:gap-4">
                    <a href="{{ route('products.index') }}" class="bg-background hover:bg-light border border-light rounded-lg p-3 sm:p-4 text-center transition">
                        <div class="text-accent mb-1 sm:mb-2">
                            <i class="fas fa-boxes text-xl sm:text-2xl"></i>
                        </div>
                        <p class="text-sm sm:text-base font-medium">Browse Products</p>
                    </a>
                    <a href="{{ route('orders.index') }}" class="bg-background hover:bg-light border border-light rounded-lg p-3 sm:p-4 text-center transition">
                        <div class="text-secondary mb-1 sm:mb-2">
                            <i class="fas fa-clipboard-list text-xl sm:text-2xl"></i>
                        </div>
                        <p class="text-sm sm:text-base font-medium">My Orders</p>
                    </a>
                    <a href="#" class="bg-background hover:bg-light border border-light rounded-lg p-3 sm:p-4 text-center transition">
                        <div class="text-primary mb-1 sm:mb-2">
                            <i class="fas fa-heart text-xl sm:text-2xl"></i>
                        </div>
                        <p class="text-sm sm:text-base font-medium">Wishlist</p>
                    </a>
                    <a href="#" class="bg-background hover:bg-light border border-light rounded-lg p-3 sm:p-4 text-center transition">
                        <div class="text-accent mb-1 sm:mb-2">
                            <i class="fas fa-cog text-xl sm:text-2xl"></i>
                        </div>
                        <p class="text-sm sm:text-base font-medium">Settings</p>
                    </a>
                </div>
            </div>

            <!-- Featured Products -->
            <div class="mt-6 sm:mt-8">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-3 sm:mb-4">
                    <h2 class="text-lg sm:text-xl font-bold text-primary mb-2 sm:mb-0">Recommended For You</h2>
                    <a href="{{ route('products.index') }}" class="text-sm sm:text-base text-accent hover:underline">View All</a>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                    <!-- Product Card 1 -->
                    <div class="bg-white rounded-lg shadow-md overflow-hidden border border-light hover:shadow-lg transition">
                        <div class="relative">
                            <img src="https://via.placeholder.com/300x200?text=Product+1" alt="Product" class="w-full h-40 sm:h-48 object-cover">
                            <button class="absolute top-1.5 sm:top-2 right-1.5 sm:right-2 bg-white rounded-full p-1.5 sm:p-2 text-accent shadow-md hover:bg-accent hover:text-white transition">
                                <i class="fas fa-heart text-sm sm:text-base"></i>
                            </button>
                        </div>
                        <div class="p-3 sm:p-4">
                            <h3 class="text-base sm:text-lg font-bold mb-1">Premium Jersey</h3>
                            <p class="text-xs sm:text-sm text-gray-600 mb-1 sm:mb-2">High quality team jersey</p>
                            <div class="flex justify-between items-center">
                                <span class="text-sm sm:text-base font-bold text-secondary">$49.99</span>
                                <button class="bg-accent text-white px-2 sm:px-3 py-0.5 sm:py-1 rounded-full text-xs sm:text-sm hover:bg-secondary transition">
                                    <i class="fas fa-cart-plus mr-0.5 sm:mr-1"></i> Add
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Product Card 2 -->
                    <div class="bg-white rounded-lg shadow-md overflow-hidden border border-light hover:shadow-lg transition">
                        <div class="relative">
                            <img src="https://via.placeholder.com/300x200?text=Product+2" alt="Product" class="w-full h-40 sm:h-48 object-cover">
                            <button class="absolute top-1.5 sm:top-2 right-1.5 sm:right-2 bg-white rounded-full p-1.5 sm:p-2 text-accent shadow-md hover:bg-accent hover:text-white transition">
                                <i class="fas fa-heart text-sm sm:text-base"></i>
                            </button>
                        </div>
                        <div class="p-3 sm:p-4">
                            <h3 class="text-base sm:text-lg font-bold mb-1">Training Shorts</h3>
                            <p class="text-xs sm:text-sm text-gray-600 mb-1 sm:mb-2">Comfortable workout shorts</p>
                            <div class="flex justify-between items-center">
                                <span class="text-sm sm:text-base font-bold text-secondary">$29.99</span>
                                <button class="bg-accent text-white px-2 sm:px-3 py-0.5 sm:py-1 rounded-full text-xs sm:text-sm hover:bg-secondary transition">
                                    <i class="fas fa-cart-plus mr-0.5 sm:mr-1"></i> Add
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Product Card 3 -->
                    <div class="bg-white rounded-lg shadow-md overflow-hidden border border-light hover:shadow-lg transition">
                        <div class="relative">
                            <img src="https://via.placeholder.com/300x200?text=Product+3" alt="Product" class="w-full h-40 sm:h-48 object-cover">
                            <button class="absolute top-1.5 sm:top-2 right-1.5 sm:right-2 bg-white rounded-full p-1.5 sm:p-2 text-accent shadow-md hover:bg-accent hover:text-white transition">
                                <i class="fas fa-heart text-sm sm:text-base"></i>
                            </button>
                        </div>
                        <div class="p-3 sm:p-4">
                            <h3 class="text-base sm:text-lg font-bold mb-1">Team Cap</h3>
                            <p class="text-xs sm:text-sm text-gray-600 mb-1 sm:mb-2">Official team cap</p>
                            <div class="flex justify-between items-center">
                                <span class="text-sm sm:text-base font-bold text-secondary">$19.99</span>
                                <button class="bg-accent text-white px-2 sm:px-3 py-0.5 sm:py-1 rounded-full text-xs sm:text-sm hover:bg-secondary transition">
                                    <i class="fas fa-cart-plus mr-0.5 sm:mr-1"></i> Add
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Dashboard Footer -->
        <div class="bg-light px-4 sm:px-6 py-3 sm:py-4 text-center">
            <p class="text-xs sm:text-sm text-primary">
                Last login: {{ Auth::user()->last_login_at ? Auth::user()->last_login_at->format('M j, Y g:i A') : 'First login' }}
            </p>
        </div>
    </div>
</div>
@endsection