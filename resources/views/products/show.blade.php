@extends('layouts.app')

@php
    $hideHero = true; // This will make the variable available to the layout
@endphp

@section('contents')
<div class="max-w-6xl mx-auto px-4 py-8">
    <div class="flex flex-col md:flex-row gap-8">
        <!-- Product Images -->
        <div class="md:w-1/2">
            <div class="bg-[#FBF8FB] rounded-lg overflow-hidden mb-4">
                <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}" class="w-full h-auto max-h-[500px] object-contain">
            </div>
            <div class="flex gap-2 overflow-x-auto py-2">
                <div class="flex-shrink-0 w-20 h-20 border border-[#FBB3C8] rounded-md overflow-hidden">
                    <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                </div>
            </div>
        </div>
        
        <!-- Product Details -->
        <div class="md:w-1/2">
            <h1 class="text-3xl font-bold text-[#3F1A2B] mb-2">{{ $product->name }}</h1>
            
            <!-- Rating -->
            <div class="flex items-center mb-4">
                <div class="flex items-center">
                    @for($i = 1; $i <= 5; $i++)
                        @if($i <= floor($product->average_rating))
                            <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        @else
                            <svg class="w-5 h-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        @endif
                    @endfor
                    <span class="ml-2 text-gray-600">
                        {{ number_format($product->average_rating, 1) }} 
                        ({{ $product->reviews_count ?? $product->reviews->count() }} reviews)
                    </span>
                </div>
            </div>
            
            <!-- Pricing -->
            <div class="mb-6">
                <span class="text-2xl font-bold text-[#B2183A]">₱{{ number_format($product->price, 2) }}</span>
                @if($product->original_price)
                    <span class="ml-2 text-lg line-through text-gray-400">₱{{ number_format($product->original_price, 2) }}</span>
                @endif
            </div>
            
            <!-- Description -->
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-[#3F1A2B] mb-2">Description</h3>
                <p class="text-gray-700">{{ $product->description }}</p>
            </div>
            
            <!-- Sizes -->
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-[#3F1A2B] mb-2">Available Sizes</h3>
                <div class="flex space-x-2">
                    <span class="w-10 h-10 flex items-center justify-center border border-[#ED4A69] rounded-full text-sm font-bold shadow-md cursor-pointer hover:bg-[#FBB3C8] transition">S</span>
                    <span class="w-10 h-10 flex items-center justify-center border border-[#ED4A69] rounded-full text-sm font-bold shadow-md cursor-pointer hover:bg-[#FBB3C8] transition">M</span>
                    <span class="w-10 h-10 flex items-center justify-center border border-[#ED4A69] rounded-full text-sm font-bold shadow-md cursor-pointer hover:bg-[#FBB3C8] transition">L</span>
                    <span class="w-10 h-10 flex items-center justify-center border border-[#ED4A69] rounded-full text-sm font-bold shadow-md cursor-pointer hover:bg-[#FBB3C8] transition">XL</span>
                </div>
            </div>
            
            <!-- Add to Cart -->
            @auth
                <form action="{{ route('cart.add', $product) }}" method="POST" class="flex space-x-2 mb-6">
                    @csrf
                    <button type="submit" class="flex-1 bg-[#3F1A2B] text-white py-3 px-6 rounded-[20px] hover:brightness-110 transition-all">
                        Add to Cart
                    </button>
                    <a href="{{ route('order.placeSingle', $product) }}" class="flex-1 text-center bg-[#B2183A] text-white py-3 px-6 rounded-[20px] hover:opacity-90 transition-all">
                        Buy Now
                    </a>
                </form>
            @else
                <div class="flex space-x-2">
                    <button onclick="openModal('login-modal')" class="flex-1 bg-[#3F1A2B] text-white py-3 px-6 rounded-[20px] hover:brightness-110 transition-all">
                        Login to Add
                    </button>
                    <button onclick="openModal('login-modal')" class="flex-1 bg-[#B2183A] text-white py-3 px-6 rounded-[20px] hover:opacity-90 transition-all">
                        Login to Order
                    </button>
                </div>
            @endauth
        </div>
    </div>
    
    <!-- Reviews Section -->
    <div class="mt-12 border-t border-[#FBB3C8] pt-8">
        <h2 class="text-2xl font-bold text-[#3F1A2B] mb-6">Customer Reviews</h2>
        
        <!-- Review Form -->
        @auth
            @if(!$product->reviews->contains('user_id', auth()->id()))
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-[#3F1A2B] mb-4">Write a Review</h3>
                    <form action="{{ route('reviews.store', $product) }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label class="block text-gray-700 mb-2">Your Rating</label>
                            <div class="flex items-center">
                                @for($i = 1; $i <= 5; $i++)
                                    <input type="radio" id="star{{ $i }}" name="rating" value="{{ $i }}" 
                                           class="hidden" {{ old('rating') == $i ? 'checked' : '' }}>
                                    <label for="star{{ $i }}" class="text-2xl cursor-pointer">
                                        <svg class="w-8 h-8 {{ old('rating') >= $i ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                    </label>
                                @endfor
                            </div>
                            @error('rating')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label for="comment" class="block text-gray-700 mb-2">Your Review</label>
                            <textarea name="comment" id="comment" rows="4" 
                                      class="w-full px-3 py-2 border border-[#FBB3C8] rounded-md focus:outline-none focus:ring-2 focus:ring-[#ED4A69]"
                            >{{ old('comment') }}</textarea>
                            @error('comment')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <button type="submit" class="bg-[#3F1A2B] text-white py-2 px-6 rounded-[20px] hover:brightness-110 transition-all">
                            Submit Review
                        </button>
                    </form>
                </div>
            @endif
        @else
            <div class="mb-8 text-center py-4 border border-[#FBB3C8] rounded-lg">
                <p class="text-gray-700">Please <button onclick="openModal('login-modal')" class="text-[#B2183A] font-medium hover:underline">login</button> to write a review.</p>
            </div>
        @endauth
        
        <!-- Reviews List -->
        <div class="space-y-6">
            @forelse($product->reviews as $review)
                <div class="border-b border-[#FBB3C8] pb-6 last:border-0">
                    <div class="flex items-center mb-2">
                        <div class="w-10 h-10 rounded-full bg-[#FBB3C8] flex items-center justify-center text-[#3F1A2B] font-bold mr-3">
                            {{ substr($review->user->name ?? 'U', 0, 1) }}
                        </div>
                        <div>
                            <h4 class="font-semibold">{{ $review->user->name ?? 'Unknown User' }}</h4>
                            <div class="flex items-center">
                                @if($review->rating)
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $review->rating)
                                            <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                            </svg>
                                        @else
                                            <svg class="w-4 h-4 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                            </svg>
                                        @endif
                                    @endfor
                                @endif
                                <span class="ml-2 text-gray-500 text-sm">{{ $review->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    </div>
                    <p class="text-gray-700 pl-13">{{ $review->comment }}</p>
                </div>
            @empty
                <div class="text-center py-8 text-gray-500">
                    <p>No reviews yet. Be the first to review this product!</p>
                </div>
            @endforelse
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const stars = document.querySelectorAll('input[name="rating"]');
        stars.forEach((star) => {
            star.addEventListener('change', function () {
                const rating = this.value;
                stars.forEach(input => {
                    const label = document.querySelector(`label[for="${input.id}"] svg`);
                    if (input.value <= rating) {
                        label.classList.remove('text-gray-300');
                        label.classList.add('text-yellow-400');
                    } else {
                        label.classList.remove('text-yellow-400');
                        label.classList.add('text-gray-300');
                    }
                });
            });
        });
    });
</script>
 
@endsection