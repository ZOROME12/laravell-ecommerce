@extends('layouts.app')

@section('contents')



<!-- Featured Products Section (No changes) -->
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
                      <!-- === Added class "add-to-cart-form" === -->
                      <form action="{{ route('cart.add', $product) }}" method="POST" class="mt-4 add-to-cart-form">
                        @csrf
                        <p class="text-[12px] text-[#3F1A2B] mb-1">Sizes:</p>
                        <div class="flex space-x-2 mb-3">
                          @foreach(['S','M','L','XL'] as $size)
                            <button
                              type="button"
                              class="size-btn w-8 h-8 flex items-center justify-center border border-[#ED4A69] rounded-full text-[12px] font-bold cursor-pointer hover:bg-[#FBB3C8] transition"
                              data-size="{{ $size }}"
                            >
                              {{ $size }}
                            </button>
                          @endforeach
                        </div>

                        <input type="hidden" name="size" class="selected-size-input" required>

                          <!-- === Added class "add-to-cart-submit" === -->
                        <button type="submit" class="add-to-cart-submit w-full bg-[#FBB3C8] hover:bg-[#B2183A] text-[#3F1A2B] hover:text-white py-2 rounded-lg font-medium transition-all duration-300">
                          Add to Cart
                        </button>
                      </form>
                    @else
                        <button onclick="openModal('login-modal')" class="mt-4 w-full bg-[#FBB3C8] hover:bg-[#B2183A] text-[#3F1A2B] hover:text-white py-2 rounded-lg font-medium transition-all duration-300">
                          Add to Cart
                        </button>
                    @endauth
                  </div>
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

<!-- === "Our Products" Section === -->
<section aria-labelledby="our-products-heading" class="py-12">
    <div class="container mx-auto px-4">
        <h1 id="our-products-heading" class="text-3xl font-bold mb-6 text-[#3F1A2B]">Our Products</h1>
        <!-- === MODIFICATION: Changed grid classes back to 1 column default === -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach ($products as $product)
                <!-- Card with h-full and flex-col (from previous fix) -->
                <div class="bg-white shadow-md overflow-hidden border border-[#FBB3C8] h-full flex flex-col transition-all duration-300 ease-in-out transform hover:scale-105 hover:shadow-lg" style="border-radius: 14px;">
                    <a href="{{ route('products.show', $product) }}">
                        <div class="w-full h-[240px] flex items-center justify-center bg-[#FBF8FB]">
                            <img src="{{ Storage::url($product->image ?? 'products/placeholder.jpg') }}"
                                alt="{{ $product->name }}"
                                class="max-w-full max-h-full object-contain hover:opacity-90 transition">
                        </div>
                    </a>
                    <!-- Card content with flex-1 (from previous fix) -->
                    <div class="p-4 flex flex-col justify-between flex-1">
                        <div> <!-- Top content block -->
                            <a href="{{ route('products.show', $product) }}" class="hover:underline">
                                <h3 class="text-[16px] font-bold text-[#3F1A2B]">{{ $product->name }}</h3>
                            </a>
                            <div class="flex items-center space-x-2">
                                <span class="text-[14px] font-bold text-[#B2183A]">₱{{ number_format($product->price, 2) }}</span>
                                @if($product->original_price)
                                    <span class="text-[14px] line-through text-gray-400">₱{{ number_format($product->original_price, 2) }}</span>
                                @endif
                            </div>
                            <!-- === MODIFICATION: Removed placeholder description text === -->
                            <p class="text-gray-600 my-3 text-sm">{{ Str::limit($product->description, 100) }}</p>
                        </div>

                        <div> <!-- Bottom content block (will be pushed down) -->
                            {{-- Rating --}}
                            @if($product->reviews_count > 0)
                                <div class="flex items-center mt-[-5px]">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= floor($product->reviews_avg_rating))
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
                                <p class="text-[12px] mt-2 text-[#3F1A2B]">Sizes:</p>
                                <!-- === Added class "add-to-cart-form" === -->
                                <form action="{{ route('cart.add', $product) }}" method="POST" class="space-y-2 add-to-cart-form">
                                    @csrf
                                    <!-- Hidden input to store the selected size -->
                                    <input type="hidden" name="size" class="selected-size-input">

                                    <!-- Clickable Size Buttons (replacing static ones) -->
                                    <div class="flex space-x-2 mt-2 mb-4">
                                        @foreach (['S', 'M', 'L', 'XL'] as $size)
                                            <button
                                                type="button"
                                                class="size-option w-8 h-8 flex items-center justify-center border border-[#ED4A69] rounded-full text-[12px] font-bold shadow-md cursor-pointer hover:bg-[#FBB3C8] transition"
                                                data-size="{{ $size }}"
                                            >
                                                {{ $size }}
                                            </button>
                                        @endforeach
                                    </div>

                                    <!-- Add to Cart & Order Now Buttons -->
                                    <div class="flex space-x-2">
                                        <!-- === Added class "add-to-cart-submit" === -->
                                        <button type="submit" class="add-to-cart-submit flex-1 bg-[#3F1A2B] text-white text-[12px] py-2 rounded-[20px] hover:brightness-110 transition-all">
                                            Add to Cart
                                        </button>
                                        <!-- === Added class "order-now-submit" === -->
                                        <button
                                            type="button"
                                            class="order-now-submit flex-1 text-center bg-[#B2183A] text-white text-[12px] py-2 rounded-[20px] hover:opacity-90 hover:scale-105 transition-transform duration-200"
                                            data-url="{{ route('order.placeSingle', $product) }}">
                                            Order Now
                                        </button>
                                    </div>
                                </form>
                            @else
                                <div class="flex space-x-2">
                                    <button onclick="openModal('login-modal')" class="flex-1 bg-[#3F1A2B] text-white text-[12px] py-2 rounded-[20px] hover:brightness-110 transition-all">
                                        Login to Add
                                    </button>
                                    <button onclick="openModal('login-modal')" class="flex-1 bg-[#B21A3A] text-white text-[12px] py-2 rounded-[20px] hover:opacity-90 transition-all">
                                        Login to Order
                                    </button>
                                </div>
                            @endauth
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>


<!-- Swiper JS (No changes) -->
<script src="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.js"></script>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    // =======================
    // Swiper Carousel
    // =======================
    new Swiper('.featured-products-carousel', {
      slidesPerView: 1,
      spaceBetween: 20,
      loop: true,
      autoplay: {
        delay: 3000, // Increased delay slightly
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

  document.addEventListener('DOMContentLoaded', () => {
    // =======================
    // Size Selection Logic (Remains the same)
    // =======================
    document.querySelectorAll('form.add-to-cart-form').forEach(form => { // Target specific forms
      const sizeButtons = form.querySelectorAll('.size-btn, .size-option');
      const sizeInput = form.querySelector('.selected-size-input');
      if (!sizeButtons.length || !sizeInput) return;

      sizeButtons.forEach(button => {
        button.addEventListener('click', e => {
          e.preventDefault(); // Keep this preventDefault
           // Find buttons only within the current form
           form.querySelectorAll('.size-btn, .size-option').forEach(b => b.classList.remove('bg-[#B2183A]', 'text-white'));
          button.classList.add('bg-[#B2183A]', 'text-white');
          sizeInput.value = button.dataset.size;
        });
      });

        // Moved size validation into the spam prevention script below
    });

     // =======================
     // Order Now Logic (Remains mostly the same, integrated with spam prevention below)
     // =======================
     // Event listener is now added in the spam prevention script
  });


  // === NEW: Spam Prevention for Product Buttons ===
  document.addEventListener('DOMContentLoaded', function () {
       const productSpamAlert = document.getElementById('spamAlert'); // Re-use alert

       function showProductAlert(message) {
           if (productSpamAlert) {
               productSpamAlert.textContent = message;
               productSpamAlert.classList.remove('hidden', 'opacity-0');
               setTimeout(() => {
                   productSpamAlert.classList.add('opacity-0');
                   setTimeout(() => productSpamAlert.classList.add('hidden'), 300);
               }, 2000); // Shorter duration for product alerts
           }
       }

       // --- Add to Cart Spam Prevention ---
       document.querySelectorAll('.add-to-cart-form').forEach(form => {
           const button = form.querySelector('.add-to-cart-submit');
           const sizeInput = form.querySelector('.selected-size-input');
           let clickCount = 0;
           const originalText = button ? button.innerHTML : 'Add to Cart'; // Store original text

           if (form && button && sizeInput) {
               form.addEventListener('submit', function (e) {
                   // 1. Check Size Selection First
                   if (!sizeInput.value) {
                       e.preventDefault();
                       alert('Please select a size before adding to cart.'); // Use alert here as it stops submission
                       clickCount = 0; // Reset count if validation fails
                       button.disabled = false; // Re-enable button
                       button.innerHTML = originalText; // Reset text
                       return;
                   }

                   // 2. Prevent Spam Click
                   clickCount++;
                   if (clickCount > 1) {
                       e.preventDefault(); // Stop extra submissions
                       showProductAlert('Adding item...'); // Show quick alert
                       return;
                   }

                   // 3. First Valid Click - Disable and Change Text
                   button.disabled = true;
                   button.innerHTML = `
                       <i class="fas fa-circle-notch fa-spin mr-1 text-xs"></i> Adding...
                   `;

                   // Allow the form to submit naturally
                   // Reset button state after a short delay in case submission fails client-side
                   setTimeout(() => {
                       if (!form.submitted) { // Check if form actually submitted (basic check)
                           button.disabled = false;
                           button.innerHTML = originalText;
                           clickCount = 0;
                       }
                   }, 1500); // Reset after 1.5 seconds if needed
               });
           }
            // Reset button if user navigates back using bfcache
            window.addEventListener('pageshow', function(event) {
               if (button) {
                   button.disabled = false;
                   button.innerHTML = originalText;
                   clickCount = 0; // Reset click count on page show
               }
            });
       });

       // --- Order Now Spam Prevention ---
       document.querySelectorAll('.order-now-submit').forEach(button => {
           let clickCount = 0;
           const originalText = button.innerHTML; // Store original text

           button.addEventListener('click', function (e) {
               e.preventDefault(); // Always prevent default for this button initially

               const form = button.closest('form'); // Find parent form
               const sizeInput = form ? form.querySelector('.selected-size-input') : null;
               const url = button.getAttribute('data-url');

               // 1. Check Size Selection First
               if (!sizeInput || !sizeInput.value) {
                   alert('Please select a size before ordering.');
                   clickCount = 0; // Reset count
                   button.disabled = false; // Re-enable
                   button.innerHTML = originalText; // Reset text
                   return;
               }

               // 2. Prevent Spam Click
               clickCount++;
               if (clickCount > 1) {
                   showProductAlert('Processing order...'); // Show quick alert
                   return; // Stop further actions
               }

               // 3. First Valid Click - Disable, Change Text, Redirect
               button.disabled = true;
               button.innerHTML = `
                   <i class="fas fa-circle-notch fa-spin mr-1 text-xs"></i> Ordering...
               `;

                // Construct URL with size and redirect
                const size = sizeInput.value;
                window.location.href = `${url}?size=${encodeURIComponent(size)}`;

                // Reset button state after a delay in case redirect fails
                setTimeout(() => {
                   button.disabled = false;
                   button.innerHTML = originalText;
                   clickCount = 0;
                }, 1500); // Reset after 1.5 seconds
           });

            // Reset button if user navigates back using bfcache
           window.addEventListener('pageshow', function(event) {
               button.disabled = false;
               button.innerHTML = originalText;
               clickCount = 0; // Reset click count on page show
           });
       });
  });
  // === END Spam Prevention ===
</script>
@endsection

