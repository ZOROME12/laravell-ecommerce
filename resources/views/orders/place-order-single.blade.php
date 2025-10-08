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

    <form method="POST" action="{{ route('order.storeSingle') }}">
        @csrf
        <input type="hidden" name="product_id" value="{{ $product->id }}">
        <input type="hidden" name="quantity" value="1">
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
                                    <td class="py-3 px-4 border-b text-center">1</td>
                                    <td class="py-3 px-4 border-b text-right font-semibold">
                                        ₱{{ number_format($product->price, 2) }}
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
                        <span class="font-medium">Order Total (1 item):</span>
                        <span class="font-bold">₱{{ number_format($product->price + 36, 2) }}</span>
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
                            <span>₱{{ number_format($product->price, 2) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Shipping Subtotal</span>
                            <span>₱36.00</span>
                        </div>
                        <div class="flex justify-between font-bold text-lg mt-2">
                            <span>Total Payment:</span>
                            <span>₱{{ number_format($product->price + 36, 2) }}</span>
                        </div>
                    </div>
                    
                    <!-- Place Order Button -->
                    <button type="submit" class="w-full bg-accent hover:bg-accent-dark text-white py-3 rounded font-semibold transition mt-4">
                        Place Order
                    </button>
                </div>
            </div>
        </div>
    </form>
@endsection
