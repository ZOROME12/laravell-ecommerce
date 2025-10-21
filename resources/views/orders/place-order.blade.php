@extends('layouts.app')

@php
    $hideHero = true;
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

    @if ($errors->any())
        <div class="bg-red-100 text-red-800 p-4 rounded mb-6">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
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
                    <div class="space-y-4 text-gray-700">
                        <div>
                            <label for="delivery_name_cart" class="block font-bold mb-1">Name</label>
                            <input type="text" id="delivery_name_cart" name="delivery_name"
                                value="{{ old('delivery_name', Auth::user()->name) }}"
                                required
                                class="w-full border rounded px-3 py-2 text-gray-700 focus:outline-none">
                            @error('delivery_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="delivery_phone_cart" class="block font-bold mb-1">Phone Number</label>
                            <div class="flex">
                                <span class="inline-flex items-center px-3 bg-gray-100 border border-r-0 rounded-l text-gray-600 text-sm">+63</span>
                                <input type="text" id="delivery_phone_cart" name="delivery_phone"
                                        value="{{ old('delivery_phone', str_replace('+63', '', Auth::user()->phone ?? '')) }}"
                                        placeholder="9XXXXXXXXX"
                                        pattern="9[0-9]{9}"
                                        title="Please enter a 10-digit number starting with 9"
                                        required
                                        class="w-full border rounded-r px-3 py-2 text-gray-700 focus:outline-none"
                                        oninput="this.value = this.value.replace(/[^0-9]/g, ''); if(this.value.length > 0 && !this.value.startsWith('9')) this.value = ''; if (this.value.length > 10) this.value = this.value.slice(0, 10);">
                            </div>
                            @error('delivery_phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
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
                                @forelse ($cartItems as $item)
                                    @php
                                        $productExists = $item->product;
                                        $stock = $productExists ? ($item->product->stock ?? 0) : 0;
                                        $price = $productExists ? ($item->product->price ?? 0) : 0;
                                        $quantity = $item->quantity ?? 1;
                                        if ($stock <= 0) {
                                            $quantity = 0;
                                        } elseif ($quantity > $stock) {
                                            $quantity = $stock;
                                        } elseif ($quantity < 1 && $stock > 0) {
                                            $quantity = 1;
                                        }
                                        $itemSubtotal = $price * $quantity;
                                    @endphp
                                    <tr>
                                        <td class="py-3 px-4 border-b">
                                            <div class="flex items-center">
                                                @if($productExists)
                                                    <img src="{{ $item->product->image ? Storage::url($item->product->image) : 'https://via.placeholder.com/50' }}"
                                                        alt="{{ $item->product->name }}"
                                                        class="w-14 h-14 object-cover rounded border mr-3" />
                                                    <div>
                                                        <p>{{ $item->product->name }}</p>
                                                        <p class="font-semibold">₱{{ number_format($price, 2) }}</p>
                                                        @if ($stock <= 0)
                                                            <p class="text-red-600 text-xs font-medium">Out of Stock</p>
                                                        @elseif ($item->quantity > $stock)
                                                            <p class="text-orange-600 text-xs font-medium">Limited stock ({{ $stock }} left)</p>
                                                        @elseif ($stock < 5)
                                                            <p class="text-orange-600 text-xs font-medium">Low stock ({{ $stock }} left)</p>
                                                        @endif
                                                    </div>
                                                @else
                                                    <div class="w-14 h-14 bg-gray-200 rounded border mr-3 flex items-center justify-center text-gray-500 text-xs">No Img</div>
                                                    <div>
                                                        <p class="text-red-600">Product unavailable</p>
                                                    </div>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="py-3 px-4 border-b text-center">
                                            {{ $item->size ?? 'N/A' }}
                                        </td>
                                        <td class="py-3 px-4 border-b text-center">
                                            {{ $quantity }}
                                            @if ($item->quantity > $stock && $stock > 0)
                                                <p class="text-xs text-orange-600">(Requested {{ $item->quantity }})</p>
                                            @endif
                                        </td>
                                        <td class="py-3 px-4 border-b text-right font-semibold">
                                            ₱{{ number_format($itemSubtotal, 2) }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-6 text-gray-500">Your cart is empty.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Right Section -->
            <div class="bg-white p-6 rounded-lg shadow h-fit">
                <div class="space-y-4">
                    @php
                        // CALCULATE MERCH SUBTOTAL (Same as before)
                        $merchSubtotal = $cartItems->sum(function($item) {
                            $stock = optional($item->product)->stock ?? 0;
                            if ($stock <= 0) return 0;
                            $quantity = min($item->quantity ?? 1, $stock);
                            $price = optional($item->product)->price ?? 0;
                            return $price * $quantity;
                        });
                        
                        // FIX: Set shipping fee to zero (0.00)
                        $shippingFee = 0.00;
                        
                        // FIX: Calculate total payment without shipping fee
                        $totalPayment = $merchSubtotal; 
                        
                        $validItemCount = $cartItems->filter(function($item) {
                            return optional($item->product)->stock > 0;
                        })->count();
                        $canPlaceOrder = $validItemCount > 0 && $merchSubtotal > 0;
                    @endphp

                    <div class="flex justify-between items-center">
                        <span class="font-medium">Order Total ({{ $validItemCount }} {{ Str::plural('item', $validItemCount) }}):</span>
                        {{-- Display the updated total --}}
                        <span class="font-bold">₱{{ number_format($totalPayment, 2) }}</span>
                    </div>

                    <!-- Payment Method -->
                    <div class="border-t pt-4">
                        <div class="flex justify-between items-center mb-2">
                            <h3 class="font-medium">Payment Method</h3>
                        </div>
                        <div class="flex items-center space-x-2 p-3 bg-blue-50 border border-blue-200 rounded-md">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-blue-600">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 0 1 5.186 7.23c-.38.054-.757.112-1.134.174C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 0 0-1.134-.174 2.31 2.31 0 0 1-1.64-1.055l-.822-1.316a2.192 2.192 0 0 0-1.736-1.039 48.774 48.774 0 0 0-5.232 0 2.192 2.192 0 0 0-1.736 1.039l-.821 1.316Z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 1 1-9 0 4.5 4.5 0 0 1 9 0ZM18.75 10.5h.008v.008h-.008V10.5Z" />
                            </svg>
                            <span class="text-blue-800 font-medium">GCash (Manual Verification)</span>
                        </div>
                        <input type="hidden" name="payment_method" value="gcash_manual">
                        <p class="text-xs text-gray-500 mt-2">You will be redirected to provide payment details after placing the order.</p>
                    </div>

                    <!-- Order Summary -->
                    <div class="border-t pt-4 space-y-2">
                        <div class="flex justify-between">
                            <span>Merchandise Subtotal</span>
                            <span>₱{{ number_format($merchSubtotal, 2) }}</span>
                        </div>
                        <div class="flex justify-between font-bold text-lg mt-2">
                            <span>Total Payment:</span>
                            {{-- FIX: Display Total Payment without shipping fee --}}
                            <span>₱{{ number_format($totalPayment, 2) }}</span>
                        </div>
                    </div>

                    <!-- Place Order Button -->
                    <button type="submit"
                            class="w-full bg-accent hover:bg-accent-dark text-white py-3 rounded font-semibold transition mt-4 disabled:opacity-50 disabled:cursor-not-allowed"
                            {{ !$canPlaceOrder ? 'disabled' : '' }}>
                        Place Order
                    </button>
                    @unless($canPlaceOrder)
                        <p class="text-center text-red-600 text-sm mt-2">Cannot place order with out-of-stock items.</p>
                    @endunless
                </div>
            </div>
        </div>
    </form>
@endsection
