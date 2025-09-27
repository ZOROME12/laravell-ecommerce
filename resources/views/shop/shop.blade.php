@extends('layouts.app')

@php
    $hideHero = true;
@endphp

@section('contents')
<div class="bg-[#FBF8FB] min-h-screen">
    <div class="max-w-7xl mx-auto px-6 py-12">
        <h1 class="text-4xl font-bold text-center mb-12 flex items-center justify-center space-x-3 text-[#3F1A2B]">
            <svg xmlns="http://www.w3.org/2000/svg" 
                class="w-10 h-10 text-[#B2183A]" 
                fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" 
                    d="M3 9.75V21h18V9.75M3 9.75L7.5 3h9L21 9.75M3 9.75h18" />
            </svg>
            <span>Shop</span>
        </h1>

        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-10">
            <div class="relative w-full md:w-1/2">
                <input type="text" placeholder="Search products..." 
                    class="w-full pl-10 pr-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-[#ED4A69] focus:border-[#ED4A69] outline-none">
                <svg class="w-5 h-5 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 103.6 3.6a7.5 7.5 0 0013.05 13.05z" />
                </svg>
            </div>

            <div>
                <select class="border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-[#ED4A69] focus:border-[#ED4A69] outline-none">
                    <option>Sort by</option>
                    <option value="latest">Latest</option>
                    <option value="price_low">Price: Low to High</option>
                    <option value="price_high">Price: High to Low</option>
                    <option value="popular">Most Popular</option>
                </select>
            </div>
        </div>

        @foreach ($categories as $category)
        <section class="mb-16 relative">
            <h2 class="text-2xl font-semibold text-gray-800 mb-6 flex items-center justify-between">
                <span class="bg-[#FBB3C8] text-[#3F1A2B] px-4 py-1 rounded-lg shadow-sm">
                    {{ $category->name }}
                </span>
            </h2>

            @if ($category->products->isEmpty())
                <p class="text-gray-500">No products available in this category yet.</p>
            @else
                <div class="relative">
                    <button 
                        data-prev="{{ $category->id }}"
                        class="absolute -left-14 top-1/2 -translate-y-1/2 z-10 bg-white text-[#3F1A2B] w-12 h-12 rounded-full shadow-lg flex items-center justify-center hover:shadow-xl hover:scale-105 transition"
                        aria-label="Scroll left for {{ $category->name }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </button>

                    <div id="carousel-{{ $category->id }}" 
                        class="flex overflow-x-auto overflow-y-hidden gap-6 scrollbar-hide scroll-smooth">
                        @foreach ($category->products as $product)
                            <div class="min-w-[280px] max-w-[280px] h-[400px] bg-white shadow-md overflow-hidden border border-[#FBB3C8] rounded-[14px] flex-shrink-0 transition-all duration-300 ease-in-out transform hover:scale-105 hover:shadow-lg">
                                <a href="{{ route('products.show', $product) }}">
                                    <div class="w-full h-[200px] flex items-center justify-center bg-[#FBF8FB]">
                                        <img src="{{ $product->image ? asset('storage/' . $product->image) : asset('storage/products/placeholder.jpg') }}"
                                            alt="{{ $product->name }}"
                                            class="max-w-full max-h-full object-contain hover:opacity-90 transition">
                                    </div>
                                </a>
                                <div class="p-4 flex flex-col justify-between h-[calc(100%-200px)]">
                                    <div>
                                        <h3 class="text-[16px] font-bold text-[#3F1A2B] truncate">{{ $product->name }}</h3>
                                        <span class="text-[14px] font-bold text-[#B2183A] block">₱{{ number_format($product->price, 2) }}</span>
                                        <p class="text-gray-600 my-2 text-sm truncate">{{ Str::limit($product->description, 80) }}</p>
                                    </div>
                                    {{-- Action Buttons --}}
                                    <div class="flex space-x-2 mt-3">
                                        @auth
                                            <form action="{{ route('cart.add', $product) }}" method="POST" class="flex flex-1 space-x-2">
                                                @csrf
                                                <button type="submit" 
                                                    class="flex-1 bg-[#3F1A2B] text-white text-[12px] py-2 rounded-[20px] 
                                                           hover:brightness-110 hover:scale-105 transition-transform duration-200">
                                                    Add to Cart
                                                </button>
                                                <a href="{{ route('order.placeSingle', $product) }}" 
                                                    class="flex-1 text-center bg-[#B2183A] text-white text-[12px] py-2 rounded-[20px] 
                                                           hover:opacity-90 hover:scale-105 transition-transform duration-200">
                                                    Order Now
                                                </a>
                                            </form>
                                        @else
                                            <button onclick="openModal('login-modal')" 
                                                class="flex-1 bg-[#3F1A2B] text-white text-[12px] py-2 rounded-[20px] 
                                                       hover:brightness-110 hover:scale-105 transition-transform duration-200">
                                                Login to Add
                                            </button>
                                            <button onclick="openModal('login-modal')" 
                                                class="flex-1 bg-[#B2183A] text-white text-[12px] py-2 rounded-[20px] 
                                                       hover:opacity-90 hover:scale-105 transition-transform duration-200">
                                                Login to Order
                                            </button>
                                        @endauth
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <button 
                        data-next="{{ $category->id }}"
                        class="absolute -right-14 top-1/2 -translate-y-1/2 z-10 bg-white text-[#3F1A2B] w-12 h-12 rounded-full shadow-lg flex items-center justify-center hover:shadow-xl hover:scale-105 transition"
                        aria-label="Scroll right for {{ $category->name }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                </div>
            @endif
        </section>
        @endforeach
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const config = {
        spaceBetween: 24 // Tailwind gap-6 ≈ 24px
    };

    document.querySelectorAll('[id^="carousel-"]').forEach(carousel => {
        const categoryId = carousel.id.replace('carousel-', '');

        const prevBtn = document.querySelector(`[data-prev="${categoryId}"]`);
        const nextBtn = document.querySelector(`[data-next="${categoryId}"]`);

        function scroll(direction) {
            const card = carousel.querySelector('div'); 
            if (!card) return;

            const cardWidth = card.offsetWidth + config.spaceBetween;
            const scrollAmount = cardWidth * 2; // Move 2 cards per click

            carousel.scrollBy({
                left: direction * scrollAmount,
                behavior: 'smooth'
            });
        }

        if (prevBtn) {
            prevBtn.addEventListener('click', () => scroll(-1));
        }
        if (nextBtn) {
            nextBtn.addEventListener('click', () => scroll(1));
        }
    });
});
</script>

<style>
/* Utility CSS for hiding scrollbars in the carousel element */
.scrollbar-hide::-webkit-scrollbar {
    display: none;
}
.scrollbar-hide {
    -ms-overflow-style: none; /* IE and Edge */
    scrollbar-width: none; /* Firefox */
}
</style>
@endsection
