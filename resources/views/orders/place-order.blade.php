@extends('layouts.app')

@php
    $hideHero = true;
@endphp

@section('contents')
    <div class="flex items-center gap-2 mb-6">
        <h1 class="text-2xl font-bold">Checkout</h1>
    </div>

    @if (session('error'))
        <div class="bg-red-100 text-red-800 p-4 rounded mb-6">
            {{ session('error') }}
        </div>
    @endif

    <form method="POST" action="{{ route('order.store') }}">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Section -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Customer Info -->
                <div class="bg-white p-6 rounded-lg shadow">
                    <h2 class="text-lg font-semibold mb-4">Customer Information</h2>

                    <label class="block font-bold mb-1">Name</label>
                    <input type="text" name="delivery_name" 
                           value="{{ old('delivery_name', Auth::user()->name) }}" 
                           required class="w-full border rounded px-3 py-2 text-gray-700" />

                    <label class="block font-bold mt-4 mb-1">Phone Number</label>
                    <input type="text" name="delivery_phone" 
                           value="{{ old('delivery_phone', Auth::user()->phone ?? '') }}" 
                           placeholder="Enter phone number"
                           required class="w-full border rounded px-3 py-2 text-gray-700" />
                </div>

                <!-- Cart Items -->
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
                                @foreach ($cartItems as $item)
                                    <tr>
                                        <td class="py-3 px-4 border-b">
                                            <div class="flex items-center">
                                                <img src="{{ $item->product->image ? Storage::url($item->product->image) : 'https://via.placeholder.com/50' }}" 
                                                     alt="{{ $item->product->name }}" 
                                                     class="w-14 h-14 object-cover rounded border mr-3" />
                                                <div>
                                                    <p>{{ $item->product->name }}</p>
                                                    <p class="font-semibold">
                                                        ₱{{ number_format($item->product->price, 2) }}
                                                    </p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-3 px-4 border-b text-center">
                                            {{ $item->size ?? 'N/A' }}
                                            <input type="hidden" name="items[{{ $item->id }}][size]" value="{{ $item->size }}">
                                        </td>
                                        <td class="py-3 px-4 border-b text-center">
                                            {{ $item->quantity }}
                                            <input type="hidden" name="items[{{ $item->id }}][quantity]" value="{{ $item->quantity }}">
                                            <input type="hidden" name="items[{{ $item->id }}][product_id]" value="{{ $item->product->id }}">
                                        </td>
                                        <td class="py-3 px-4 border-b text-right font-semibold">
                                            ₱{{ number_format($item->product->price * $item->quantity, 2) }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Right Section -->
            <div class="bg-white p-6 rounded-lg shadow h-fit">
                <div class="space-y-4">
                    <!-- Order Total -->
                    <div class="flex justify-between items-center">
                        <span class="font-medium">Order Total:</span>
                        <span class="font-bold">
                            ₱{{ number_format($cartItems->sum(fn($item) => $item->product->price * $item->quantity) + 36, 2) }}
                        </span>
                    </div>

                    <!-- Payment Method -->
                    <div class="border-t pt-4">
                        <h3 class="font-medium mb-2">Payment Method</h3>
                        <div class="flex items-center space-x-2">
                            <input type="radio" name="payment_method" value="cod" checked class="accent-accent">
                            <span>Cash on Delivery</span>
                        </div>
                    </div>

                    <!-- Place Order Button -->
                    <button type="submit" 
                            class="w-full bg-accent hover:bg-accent-dark text-white py-3 rounded font-semibold transition mt-4">
                        Place Order
                    </button>
                </div>
            </div>
        </div>
    </form>
@endsection
