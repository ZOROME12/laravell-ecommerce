@extends('layouts.app')

@php
    $hideHero = true;
    $selectedSize = request('size'); // from Buy Now URL ?size=S
@endphp

@section('contents')

    <div class="flex items-center gap-2 mb-6">
        <h1 class="text-2xl font-bold">Checkout</h1>
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-gray-700">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 7.5h1.5m-1.5 3h1.5m-7.5 3h7.5m-7.5 3h7.5m3-9h3.375c.621 0 1.125.504 1.125 1.125V18a2.25 2.25 0 0 1-2.25 2.25M16.5 7.5V18a2.25 2.25 0 0 0 2.25 2.25M16.5 7.5V4.875c0-.621-.504-1.125-1.125-1.125H4.125C3.504 3.75 3 4.254 3 4.875V18a2.25 2.25 0 0 0 2.25 2.25h13.5M6 7.5h3v3H6v-3Z" />
        </svg>
    </div>

    @if (session('error'))
        <div class="bg-red-100 text-red-800 p-4 rounded mb-6">
            {{ session('error') }}
        </div>
    @endif

    <form method="POST" action="{{ route('order.storeSingle') }}" id="checkoutForm">
        @csrf
        <input type="hidden" name="product_id" value="{{ $product->id }}">
        <input type="hidden" name="size" value="{{ $selectedSize }}">

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Section -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Customer Info (No Address) -->
                <div class="bg-white p-6 rounded-lg shadow">
                    <h2 class="text-lg font-semibold mb-4">Customer Information</h2>
                    <div class="space-y-4 text-gray-700">
                        <div>
                            <label class="block font-bold mb-1">Name</label>
                            <input type="text" name="delivery_name"
                                value="{{ old('delivery_name', Auth::user()->name) }}"
                                required
                                class="w-full border rounded px-3 py-2 text-gray-700">
                        </div>

                        <div>
                            <label class="block font-bold mb-1">Phone Number</label>
                            <input type="text" name="delivery_phone"
                                value="{{ old('delivery_phone', Auth::user()->phone ?? '') }}"
                                placeholder="Enter phone number"
                                required
                                class="w-full border rounded px-3 py-2 text-gray-700">
                        </div>
                    </div>
                </div>

                <!-- Products Ordered -->
                <div class="bg-white p-6 rounded-lg shadow">
                    <h2 class="text-lg font-semibold mb-4">Products Ordered</h2>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="text-left py-2 px-4">Product</th>
                                    <th class="text-center py-2 px-4">Size</th>
                                    <th class="text-center py-2 px-4">Quantity</th>
                                    <th class="text-right py-2 px-4">Item Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="py-3 px-4 border-b">
                                        <div class="flex items-center">
                                            <img src="{{ $product->image ? Storage::url($product->image) : 'https://via.placeholder.com/50' }}" 
                                                 alt="{{ $product->name }}" 
                                                 class="w-14 h-14 object-cover rounded border mr-3" />
                                            <div>
                                                <p>{{ $product->name }}</p>
                                                <p class="text-sm text-gray-500">Variation: DEEP BLUE</p>
                                                <p class="font-semibold">₱{{ number_format($product->price, 2) }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-3 px-4 border-b text-center">
                                        {{ $selectedSize ?? 'N/A' }}
                                    </td>
                                    <td class="py-3 px-4 border-b text-center">
                                        <input 
                                            type="number" 
                                            name="quantity" 
                                            id="quantity" 
                                            value="1" 
                                            min="1" 
                                            max="{{ $product->stock }}" 
                                            required
                                            class="w-20 border rounded px-2 py-1 text-center text-gray-700">
                                        <p id="stockNotice" class="text-red-600 text-sm mt-1 hidden">
                                            Low on stock or quantity exceeds available stock ({{ $product->stock }} left).
                                        </p>
                                        <p id="outOfStockNotice" class="text-red-600 text-sm mt-1 hidden">
                                            This item is out of stock.
                                        </p>
                                    </td>
                                    <td class="py-3 px-4 border-b text-right font-semibold">
                                        ₱<span id="itemSubtotal">{{ number_format($product->price, 2) }}</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Right Section: Summary -->
            <div class="bg-white p-6 rounded-lg shadow h-fit">
                <div class="space-y-4">
                    <!-- Order Total -->
                    <div class="flex justify-between items-center">
                        <span class="font-medium">Order Total (<span id="totalItems">1</span> item):</span>
                        <span class="font-bold">₱<span id="totalAmount">{{ number_format($product->price + 36, 2) }}</span></span>
                    </div>
                    
                    <!-- Payment Method -->
                    <div class="border-t pt-4">
                        <div class="flex justify-between items-center mb-2">
                            <h3 class="font-medium">Payment Method</h3>
                            <button type="button" class="text-accent font-medium">CHANGE</button>
                        </div>
                        <div class="flex items-center space-x-2">
                            <input type="radio" name="payment_method" value="cod" checked class="accent-accent">
                            <span>Cash on Delivery</span>
                        </div>
                    </div>
                    
                    <!-- Order Summary -->
                    <div class="border-t pt-4 space-y-2">
                        <div class="flex justify-between">
                            <span>Merchandise Subtotal</span>
                            <span>₱<span id="subtotalDisplay">{{ number_format($product->price, 2) }}</span></span>
                        </div>
                        <div class="flex justify-between">
                            <span>Shipping Subtotal</span>
                            <span>₱36.00</span>
                        </div>
                        <div class="flex justify-between font-bold text-lg mt-2">
                            <span>Total Payment:</span>
                            <span>₱<span id="finalTotal">{{ number_format($product->price + 36, 2) }}</span></span>
                        </div>
                    </div>
                    
                    <!-- Place Order Button -->
                    <button type="submit" id="placeOrderBtn" class="w-full bg-accent hover:bg-accent-dark text-white py-3 rounded font-semibold transition mt-4">
                        Place Order
                    </button>
                </div>
            </div>
        </div>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const quantityInput = document.getElementById('quantity');
            const stockNotice = document.getElementById('stockNotice');
            const outOfStockNotice = document.getElementById('outOfStockNotice');
            const placeOrderBtn = document.getElementById('placeOrderBtn');
            const subtotalDisplay = document.getElementById('subtotalDisplay');
            const itemSubtotal = document.getElementById('itemSubtotal');
            const totalAmount = document.getElementById('totalAmount');
            const finalTotal = document.getElementById('finalTotal');
            const totalItems = document.getElementById('totalItems');

            // numeric values from blade
            const productPrice = parseFloat({{ $product->price }});
            const productStock = parseInt({{ $product->stock }}, 10);
            const shipping = 36;

            // Initial state: if stock is zero, disable order
            function applyStockState(qty) {
                if (productStock <= 0) {
                    outOfStockNotice.classList.remove('hidden');
                    stockNotice.classList.add('hidden');
                    placeOrderBtn.disabled = true;
                    placeOrderBtn.classList.add('opacity-50', 'cursor-not-allowed');
                    quantityInput.value = 0;
                    subtotalDisplay.textContent = (0).toLocaleString();
                    itemSubtotal.textContent = (0).toLocaleString();
                    finalTotal.textContent = (0).toLocaleString();
                    totalAmount.textContent = (0).toLocaleString();
                    totalItems.textContent = 0;
                    return;
                }

                // clamp qty between 1 and productStock
                let clampedQty = parseInt(qty, 10) || 1;
                if (clampedQty < 1) clampedQty = 1;
                if (clampedQty > productStock) clampedQty = productStock;

                // ensure input shows clamped value
                if (parseInt(quantityInput.value, 10) !== clampedQty) {
                    quantityInput.value = clampedQty;
                }

                // show stock notice only when user attempted above stock (optional UX)
                if (qty > productStock) {
                    stockNotice.classList.remove('hidden');
                    placeOrderBtn.disabled = true;
                    placeOrderBtn.classList.add('opacity-50', 'cursor-not-allowed');
                } else {
                    stockNotice.classList.add('hidden');
                    placeOrderBtn.disabled = false;
                    placeOrderBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                }

                // update totals
                const subtotal = productPrice * clampedQty;
                subtotalDisplay.textContent = subtotal.toLocaleString();
                itemSubtotal.textContent = subtotal.toLocaleString();
                finalTotal.textContent = (subtotal + shipping).toLocaleString();
                totalAmount.textContent = (subtotal + shipping).toLocaleString();
                totalItems.textContent = clampedQty;
            }

            // initialize with current value
            applyStockState(parseInt(quantityInput.value, 10));

            // on input, clamp and update
            quantityInput.addEventListener('input', function(e) {
                const rawVal = parseInt(this.value, 10) || 0;
                applyStockState(rawVal);
            });

            // prevent form submit if quantity is zero or exceeds stock (redundant safety)
            document.getElementById('checkoutForm').addEventListener('submit', function(e) {
                const qty = parseInt(quantityInput.value, 10) || 0;
                if (productStock <= 0 || qty < 1 || qty > productStock) {
                    e.preventDefault();
                    stockNotice.classList.remove('hidden');
                    placeOrderBtn.disabled = true;
                    placeOrderBtn.classList.add('opacity-50', 'cursor-not-allowed');
                }
            });
        });
    </script>
@endsection
