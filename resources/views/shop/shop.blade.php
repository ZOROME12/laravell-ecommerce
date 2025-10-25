@extends('layouts.app')

@php
    $hideHero = true; // Variable to hide the hero section on the shop page
@endphp

@section('contents')
<div class="bg-[#FBF8FB] min-h-screen">
    <div class="max-w-7xl mx-auto px-6 py-12">
        <!-- Header -->
        <h1 class="text-4xl font-bold text-center mb-12 flex items-center justify-center space-x-3 text-[#3F1A2B]">
            <svg xmlns="http://www.w3.org/2000/svg"
                 class="w-10 h-10 text-[#B2183A]"
                 fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M3 9.75V21h18V9.75M3 9.75L7.5 3h9L21 9.75M3 9.75h18" />
            </svg>
            <span>Shop</span>
        </h1>

        <!-- Categories Loop -->
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
                    <!-- Prev Button -->
                    <button
                        data-prev="{{ $category->id }}"
                        class="absolute -left-14 top-1/2 -translate-y-1/2 z-10 bg-white text-[#3F1A2B] w-12 h-12 rounded-full shadow-lg flex items-center justify-center hover:shadow-xl hover:scale-105 transition"
                        aria-label="Scroll left for {{ $category->name }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </button>

                    <!-- Carousel -->
                    <div id="carousel-{{ $category->id }}"
                         class="flex overflow-x-auto overflow-y-hidden gap-6 scrollbar-hide scroll-smooth">
                        @foreach ($category->products as $product)
                            <div class="min-w-[280px] max-w-[280px] h-[430px] bg-white shadow-md overflow-hidden border border-[#FBB3C8] rounded-[14px] flex-shrink-0 transition-all duration-300 ease-in-out transform hover:scale-105 hover:shadow-lg">
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

                                        <!-- Size selector -->
                                        @auth
                                        <div class="mt-2">
                                            <p class="text-[12px] text-[#3F1A2B] mb-1">Size:</p>
                                            <div class="flex space-x-2 mb-2">
                                                @foreach(['S','M','L','XL'] as $size)
                                                    <button
                                                        type="button"
                                                        class="size-btn w-8 h-8 flex items-center justify-center border border-[#ED4A69] rounded-full text-[12px] font-bold cursor-pointer hover:bg-[#FBB3C8] transition"
                                                        data-size="{{ $size }}">
                                                        {{ $size }}
                                                    </button>
                                                @endforeach
                                            </div>
                                        </div>
                                        @endauth
                                    </div>

                                    <!-- Action Buttons -->
                                    <div class="flex space-x-2 mt-2">
                                        @auth
                                            <!-- === Added class "add-to-cart-form" === -->
                                            <form action="{{ route('cart.add', $product) }}" method="POST" class="add-to-cart-form flex flex-1 space-x-2">
                                                @csrf
                                                <input type="hidden" name="size" class="selected-size-input" required>
                                                <!-- === Added class "add-to-cart-submit" === -->
                                                <button type="submit"
                                                    class="add-to-cart-submit flex-1 bg-[#3F1A2B] text-white text-[12px] py-2 rounded-[20px]
                                                           hover:brightness-110 hover:scale-105 transition-transform duration-200">
                                                    Add to Cart
                                                </button>
                                                <!-- === Added class "order-now-btn" === -->
                                                <button type="button"
                                                    class="order-now-btn flex-1 text-center bg-[#B2183A] text-white text-[12px] py-2 rounded-[20px]
                                                           hover:opacity-90 hover:scale-105 transition-transform duration-200"
                                                    data-url="{{ route('order.placeSingle', $product) }}">
                                                    Order Now
                                                </button>
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

                    <!-- Next Button -->
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
document.addEventListener('DOMContentLoaded', () => {
    // =======================
    // Size Selection (Generic for all forms with this structure)
    // =======================
    document.querySelectorAll('.add-to-cart-form').forEach(form => {
        const productCard = form.closest('.p-4'); // Get parent context
        if (!productCard) return;

        const sizeButtons = productCard.querySelectorAll('.size-btn'); // Select buttons within the card context
        const sizeInput = form.querySelector('.selected-size-input');
        let selectedSize = null;

        if (!sizeButtons.length || !sizeInput) return; // Skip if elements missing

        sizeButtons.forEach(button => {
            button.addEventListener('click', e => {
                e.preventDefault();
                // Deactivate buttons only within this specific card
                productCard.querySelectorAll('.size-btn').forEach(b => b.classList.remove('bg-[#B2183A]', 'text-white'));
                button.classList.add('bg-[#B2183A]', 'text-white');
                selectedSize = button.dataset.size;
                sizeInput.value = selectedSize; // Update the hidden input in this specific form
            });
        });

        // Validation moved to spam prevention script
    });

    // =======================
    // Carousel Scroll
    // =======================
    const spaceBetween = 24; // Corresponds to gap-6
    document.querySelectorAll('[id^="carousel-"]').forEach(carousel => {
        const categoryId = carousel.id.replace('carousel-', '');
        const prevBtn = document.querySelector(`[data-prev="${categoryId}"]`);
        const nextBtn = document.querySelector(`[data-next="${categoryId}"]`);

        const scrollAmount = () => {
            const card = carousel.querySelector('div[class*="min-w-"]'); // Find a product card
            return card ? card.offsetWidth + spaceBetween : 300; // Use card width + gap
        };

        prevBtn?.addEventListener('click', () => {
            carousel.scrollBy({ left: -scrollAmount(), behavior: 'smooth' }); // Scroll by one card width + gap
        });

        nextBtn?.addEventListener('click', () => {
            carousel.scrollBy({ left: scrollAmount(), behavior: 'smooth' }); // Scroll by one card width + gap
        });
    });
});

// === NEW: Spam Prevention Script Copied from home.blade.php ===
document.addEventListener('DOMContentLoaded', function () {
      const productSpamAlert = document.getElementById('spamAlert'); // Re-use alert from layout

      function showProductAlert(message) {
          if (productSpamAlert) {
              productSpamAlert.textContent = message;
              productSpamAlert.classList.remove('hidden', 'opacity-0');
              setTimeout(() => {
                  productSpamAlert.classList.add('opacity-0');
                  setTimeout(() => productSpamAlert.classList.add('hidden'), 300);
              }, 2000); // Shorter duration
          }
      }

      // --- Add to Cart Spam Prevention ---
      document.querySelectorAll('.add-to-cart-form').forEach(form => {
          const button = form.querySelector('.add-to-cart-submit'); // Use the correct class if changed
          const sizeInput = form.querySelector('.selected-size-input');
          let clickCount = 0;
          const originalText = button ? button.innerHTML : 'Add to Cart';

          if (form && button && sizeInput) {
              form.addEventListener('submit', function (e) {
                  // 1. Check Size Selection First
                  if (!sizeInput.value) {
                      e.preventDefault();
                      alert('Please select a size before adding to cart.');
                      clickCount = 0;
                      if(button) { // Check if button exists before modifying
                          button.disabled = false;
                          button.innerHTML = originalText;
                      }
                      return;
                  }

                  // 2. Prevent Spam Click
                  clickCount++;
                  if (clickCount > 1) {
                      e.preventDefault();
                      showProductAlert('Adding item...');
                      return;
                  }

                  // 3. First Valid Click - Disable and Change Text
                  if(button) { // Check if button exists
                      button.disabled = true;
                      button.innerHTML = `<i class="fas fa-circle-notch fa-spin mr-1 text-xs"></i> Adding...`;
                  }

                  // Allow the form to submit naturally
                  // Reset button state after a short delay
                   setTimeout(() => {
                       // Basic check if form is still processing - might need refinement
                       // You could potentially check if the button is still disabled
                       if (button && button.disabled) {
                            button.disabled = false;
                            button.innerHTML = originalText;
                            clickCount = 0;
                       }
                   }, 1500);
              });
          }
           // Reset button if user navigates back using bfcache
           window.addEventListener('pageshow', function(event) {
              if (button) {
                  button.disabled = false;
                  button.innerHTML = originalText;
                  clickCount = 0;
              }
           });
      });

      // --- Order Now Spam Prevention ---
      // Uses '.order-now-btn' class from the shop page HTML
      document.querySelectorAll('.order-now-btn').forEach(button => {
          let clickCount = 0;
          const originalText = button.innerHTML;

           button.addEventListener('click', function (e) {
                e.preventDefault();

                const form = button.closest('form'); // The button is inside the form now
                const sizeInput = form ? form.querySelector('.selected-size-input') : null;
                const url = button.getAttribute('data-url');

                // 1. Check Size Selection First
                if (!sizeInput || !sizeInput.value) {
                    alert('Please select a size before ordering.');
                    clickCount = 0;
                    button.disabled = false;
                    button.innerHTML = originalText;
                    return;
                }

                // 2. Prevent Spam Click
                clickCount++;
                 if (clickCount > 1) {
                    showProductAlert('Processing order...');
                    return;
                }

                // 3. First Valid Click - Disable, Change Text, Redirect
                button.disabled = true;
                button.innerHTML = `<i class="fas fa-circle-notch fa-spin mr-1 text-xs"></i> Ordering...`;

                 const size = sizeInput.value;
                 window.location.href = `${url}?size=${encodeURIComponent(size)}`;

                 // Reset button state after delay
                 setTimeout(() => {
                    button.disabled = false;
                    button.innerHTML = originalText;
                    clickCount = 0;
                 }, 1500);
           });

            // Reset button if user navigates back using bfcache
           window.addEventListener('pageshow', function(event) {
               button.disabled = false;
               button.innerHTML = originalText;
               clickCount = 0;
           });
      });
  });
  // === END Spam Prevention ===
</script>


<style>
/* Simple scrollbar hiding */
.scrollbar-hide::-webkit-scrollbar { display: none; }
.scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
</style>
@endsection
