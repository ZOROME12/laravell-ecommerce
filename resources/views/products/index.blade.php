@extends('layouts.app')

@section('contents')



<!-- Featured Products Section -->
<section aria-labelledby="featured-products-heading" class="py-12 bg-[#FFF9FB]">
  <div class="container mx-auto px-4">
    <!-- Section Header -->
    <header class="text-center mb-10">
    <h2 id="featured-products-heading" class="text-3xl font-bold text-[#3F1A2B] mb-2 flex items-center justify-center gap-2">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-[#B2183A]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m8.485-8.485h1M3.515 12.515h1m12.02 5.657l.707.707M5.657 5.657l.707.707m12.02 0l-.707.707M5.657 18.343l-.707.707M12 8a4 4 0 100 8 4 4 0 000-8z"/>
    </svg>
        Featured Products
    </h2>
      <p class="text-[#7A4A58] max-w-2xl mx-auto">
        Discover our most popular items loved by customers
      </p>
    </header>

    <!-- Carousel Container -->
    <div class="relative">
      <!-- Carousel -->
      <div class="swiper featured-products-carousel">
        <div class="swiper-wrapper">
          @foreach ($products as $product)
            <div class="swiper-slide">
              <article class="group bg-white rounded-xl shadow-sm hover:shadow-md transition-all duration-300 overflow-hidden border border-[#FBB3C8]/50 hover:border-[#FBB3C8] h-[470px] flex flex-col">
                <!-- Product Image -->
                <figure class="relative overflow-hidden">
                  <a href="{{ route('products.show', $product) }}" aria-label="View {{ $product->name }} details">
                    <img 
                      src="{{ Storage::url($product->image ?? 'products/placeholder.jpg') }}" 
                      alt="{{ $product->name }}" 
                      class="w-full h-72 object-cover transition duration-500 group-hover:scale-105"
                      loading="lazy"
                    >
                    @if($product->original_price && $product->price < $product->original_price)
                      <span class="absolute top-4 right-4 bg-[#B2183A] text-white text-xs font-bold px-2 py-1 rounded-full">
                        {{ round(100 - ($product->price / $product->original_price * 100)) }}% OFF
                      </span>
                    @endif
                  </a>
                </figure>

                <!-- Product Info -->
                <div class="p-5 flex-grow flex flex-col">
                  <h3 class="font-bold text-[#3F1A2B] text-lg mb-2">
                    <a href="{{ route('products.show', $product) }}" class="hover:text-[#B2183A] transition">
                      {{ $product->name }}
                    </a>
                  </h3>
                  
                  <!-- Pricing -->
                  <div class="mt-auto">
                    <div class="flex items-center gap-2">
                      <p class="text-[#B2183A] font-bold text-lg">₱{{ number_format($product->price, 2) }}</p>
                      @if($product->original_price)
                        <p class="text-gray-400 text-sm line-through">₱{{ number_format($product->original_price, 2) }}</p>
                      @endif
                    </div>
                    
                    <!-- Rating (optional - add if you have ratings) -->
                    {{-- Rating Section --}}
                @if($product->reviews_count > 0)
            <div class="flex items-center mt-1">
                @for($i = 1; $i <= 5; $i++)
                    @if($i <= floor($product->reviews_avg_rating))
                        {{-- Full star --}}
                        <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.975a1 1 0 00.95.69h4.184c.969 0 1.371 1.24.588 1.81l-3.39 2.462a1 1 0 00-.364 1.118l1.287 3.975c.3.921-.755 1.688-1.54 1.118l-3.39-2.462a1 1 0 00-1.175 0l-3.39 2.462c-.784.57-1.838-.197-1.539-1.118l1.287-3.975a1 1 0 00-.364-1.118L2.043 9.402c-.783-.57-.38-1.81.588-1.81h4.184a1 1 0 00.95-.69l1.286-3.975z"/>
                        </svg>
                    @else
                        {{-- Empty star --}}
                        <svg class="w-4 h-4 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.975a1 1 0 00.95.69h4.184c.969 0 1.371 1.24.588 1.81l-3.39 2.462a1 1 0 00-.364 1.118l1.287 3.975c.3.921-.755 1.688-1.54 1.118l-3.39-2.462a1 1 0 00-1.175 0l-3.39 2.462c-.784.57-1.838-.197-1.539-1.118l1.287-3.975a1 1 0 00-.364-1.118L2.043 9.402c-.783-.57-.38-1.81.588-1.81h4.184a1 1 0 00.95-.69l1.286-3.975z"/>
                        </svg>
                    @endif
                @endfor

                {{-- Numeric Rating --}}
                <span class="ml-2 text-gray-500 text-xs">
                    {{ number_format($product->reviews_avg_rating, 1) }} ({{ $product->reviews_count }})
                </span>
            </div>
        @endif

                  
                  <!-- Add to Cart Button -->
                  @auth
                        <form action="{{ route('cart.add', $product) }}" method="POST">
                            @csrf
                            <button type="submit" class="mt-4 w-full bg-[#FBB3C8] hover:bg-[#B2183A] text-[#3F1A2B] hover:text-white py-2 rounded-lg font-medium transition-all duration-300">
                                Add to Cart
                            </button>
                        </form>
                    @else
                        <button onclick="openModal('login-modal')" class="mt-4 w-full bg-[#FBB3C8] hover:bg-[#B2183A] text-[#3F1A2B] hover:text-white py-2 rounded-lg font-medium transition-all duration-300">
                            Add to Cart
                        </button>
                    @endauth
                </div>
              </article>
            </div>
          @endforeach
        </div>
      </div>

      <!-- Navigation Buttons -->
      <button class="featured-products-prev absolute left-0 top-1/2 -translate-y-1/2 z-10 w-10 h-10 flex items-center justify-center bg-white rounded-full shadow-md hover:bg-[#FBB3C8] transition-all duration-300 -ml-5">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-[#3F1A2B]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
        </svg>
        <span class="sr-only">Previous</span>
      </button>
      
      <button class="featured-products-next absolute right-0 top-1/2 -translate-y-1/2 z-10 w-10 h-10 flex items-center justify-center bg-white rounded-full shadow-md hover:bg-[#FBB3C8] transition-all duration-300 -mr-5">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-[#3F1A2B]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
        <span class="sr-only">Next</span>
      </button>
    </div>

    <!-- Pagination -->
    <div class="featured-products-pagination mt-8 flex justify-center gap-2"></div>
  </div>
</section>

   <h1 class="text-3xl font-bold mb-6 text-[#3F1A2B]">Our Products</h1>
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
        @foreach ($products as $product)
            <div class="bg-white shadow-md overflow-hidden border border-[#FBB3C8] h-[520px] transition-all duration-300 ease-in-out transform hover:scale-105 hover:shadow-lg" style="border-radius: 14px;">
                <a href="{{ route('products.show', $product) }}">
                    <div class="w-full h-[240px] flex items-center justify-center bg-[#FBF8FB]">
    <img src="{{ Storage::url($product->image ?? 'products/placeholder.jpg') }}"
         alt="{{ $product->name }}"
         class="max-w-full max-h-full object-contain hover:opacity-90 transition">
</div>
                </a>
                <div class="p-4 flex flex-col justify-between h-[calc(100%-240px)]">
                    <div>
                        <a href="{{ route('products.show', $product) }}" class="hover:underline">   
                            <h3 class="text-[16px] font-bold text-[#3F1A2B]">{{ $product->name }}</h3> </a>
                        <div class="flex items-center space-x-2">
                            <span class="text-[14px] font-bold text-[#B2183A]">₱{{ number_format($product->price, 2) }}</span>
                            @if($product->original_price)
                                <span class="text-[14px] line-through text-gray-400">₱{{ number_format($product->original_price, 2) }}</span>
                            @endif
                        </div>
                        <p class="text-gray-600 my-3 text-sm">{{ Str::limit($product->description, 100) }}</p>
                        <p class="text-[12px] mt-2 text-[#3F1A2B]">Sizes:</p>
                        <div class="flex space-x-2 mt-2 mb-4">
                            <span class="w-8 h-8 flex items-center justify-center border border-[#ED4A69] rounded-full text-[12px] font-bold shadow-md cursor-pointer hover:bg-[#FBB3C8] transition">S</span>
                            <span class="w-8 h-8 flex items-center justify-center border border-[#ED4A69] rounded-full text-[12px] font-bold shadow-md cursor-pointer hover:bg-[#FBB3C8] transition">M</span>
                            <span class="w-8 h-8 flex items-center justify-center border border-[#ED4A69] rounded-full text-[12px] font-bold shadow-md cursor-pointer hover:bg-[#FBB3C8] transition">L</span>
                            <span class="w-8 h-8 flex items-center justify-center border border-[#ED4A69] rounded-full text-[12px] font-bold shadow-md cursor-pointer hover:bg-[#FBB3C8] transition">XL</span>
                        </div>
                    </div>

                    
                    {{-- Rating --}}
                    @if($product->reviews_count > 0)
                        <div class="flex items-center mt-1">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= floor($product->reviews_avg_rating))
                                    {{-- Full star --}}
                                    <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.975a1 
                                            1 0 00.95.69h4.184c.969 0 1.371 1.24.588 1.81l-3.39 
                                            2.462a1 1 0 00-.364 1.118l1.287 3.975c.3.921-.755 
                                            1.688-1.54 1.118l-3.39-2.462a1 1 0 00-1.175 0l-3.39 
                                            2.462c-.784.57-1.838-.197-1.539-1.118l1.287-3.975a1 
                                            1 0 00-.364-1.118L2.043 9.402c-.783-.57-.38-1.81.588-
                                            1.81h4.184a1 1 0 00.95-.69l1.286-3.975z"/>
                                    </svg>
                                @else
                                    {{-- Empty star --}}
                                    <svg class="w-4 h-4 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.975a1 
                                            1 0 00.95.69h4.184c.969 0 1.371 1.24.588 1.81l-3.39 
                                            2.462a1 1 0 00-.364 1.118l1.287 3.975c.3.921-.755 
                                            1.688-1.54 1.118l-3.39-2.462a1 1 0 00-1.175 0l-3.39 
                                            2.462c-.784.57-1.838-.197-1.539-1.118l1.287-3.975a1 
                                            1 0 00-.364-1.118L2.043 9.402c-.783-.57-.38-1.81.588-
                                            1.81h4.184a1 1 0 00.95-.69l1.286-3.975z"/>
                                    </svg>
                                @endif
                            @endfor
                            <span class="ml-2 text-gray-500 text-xs">
                                {{ number_format($product->reviews_avg_rating, 1) }} ({{ $product->reviews_count }})
                            </span>
                        </div>
                    @endif
                            @auth
                        <form action="{{ route('cart.add', $product) }}" method="POST" class="flex space-x-2">
                            @csrf
                            <button type="submit" class="flex-1 bg-[#3F1A2B] text-white text-[12px] py-2 rounded-[20px] hover:brightness-110 transition-all">
                                Add to Cart
                            </button>
                            <a href="{{ route('order.placeSingle', $product) }}" class="flex-1 text-center bg-[#B2183A] text-white text-[12px] py-2 rounded-[20px] hover:opacity-90 transition-all">
                               Order Now
                            </a>
                        </form>
                    @else
                        <div class="flex space-x-2">
                            <button onclick="openModal('login-modal')" class="flex-1 bg-[#3F1A2B] text-white text-[12px] py-2 rounded-[20px] hover:brightness-110 transition-all">
                                Login to Add
                            </button>
                            <button onclick="openModal('login-modal')" class="flex-1 bg-[#B2183A] text-white text-[12px] py-2 rounded-[20px] hover:opacity-90 transition-all">
                                Login to Order
                            </button>
                        </div>
                    @endauth
                </div>
            </div>
        @endforeach
    </div>


    <!-- Swiper JS -->
<script src="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.js"></script>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    new Swiper('.featured-products-carousel', {
      slidesPerView: 1,
      spaceBetween: 20,
      loop: true,
      autoplay: {
        delay: 1000,
        disableOnInteraction: false,
        pauseOnMouseEnter: true,
      },
      pagination: {
        el: '.featured-products-pagination',
        clickable: true,
        bulletClass: 'w-3 h-3 rounded-full bg-[#FBB3C8] opacity-40',
        bulletActiveClass: 'opacity-100 bg-[#B2183A]',
      },
      navigation: {
        nextEl: '.featured-products-next',
        prevEl: '.featured-products-prev',
      },
      breakpoints: {
        640: { slidesPerView: 2 },
        1024: { slidesPerView: 3 },
        1280: { slidesPerView: 4 }
      },
      a11y: {
        prevSlideMessage: 'Previous featured products',
        nextSlideMessage: 'Next featured products',
      }
    });
  });
</script>


@endsection