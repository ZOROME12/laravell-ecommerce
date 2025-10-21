@extends('layouts.app')

@php
    $hideHero = true;
@endphp

@section('contents')

<div class="flex flex-col lg:flex-row justify-center gap-8 mt-10 w-full px-4 lg:px-0">

    <!-- Left: Order Summary -->
    <div class="flex-1 max-w-3xl">
        {{-- *** MODIFIED: Changed heading text and color *** --}}
        <h1 class="text-3xl font-bold text-blue-600 text-center lg:text-left">Payment Submitted!</h1>

        {{-- *** MODIFIED: Updated descriptive text for verification status *** --}}
        <p class="text-gray-700 text-lg text-center lg:text-left mt-2">
            Thank you, {{ $order->delivery_name ?? 'Customer' }}. Your payment proof has been submitted.
        </p>
        <p class="text-gray-600 text-center lg:text-left">
            We are now verifying your payment. You will receive a notification once it is approved and processing begins.
        </p>
        <p class="text-gray-500 text-center lg:text-left mt-2">
            Order ID: <span class="font-semibold">{{ $order->order_id }}</span>
        </p>

        <!-- Products Ordered (No changes needed here) -->
        <div class="bg-white p-6 rounded-lg shadow mt-6">
            <h2 class="text-xl font-semibold mb-4">Products Ordered</h2>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="text-left py-2 px-4">Product</th>
                            <th class="text-center py-2 px-4">Size</th>
                            <th class="text-center py-2 px-4">Qty</th>
                            <th class="text-right py-2 px-4">Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($order->items as $item)
                            <tr>
                                <td class="py-3 px-4 border-b flex items-center">
                                    <img src="{{ $item->product->image ? Storage::url($item->product->image) : 'https://via.placeholder.com/50' }}" 
                                         alt="{{ $item->product->name }}" 
                                         class="w-14 h-14 object-cover rounded border mr-3" />
                                    <div>
                                        <p>{{ $item->product->name }}</p>
                                        @if($item->size)
                                            <p class="text-sm text-gray-500">Size: {{ $item->size }}</p>
                                        @endif
                                    </div>
                                </td>
                                <td class="py-3 px-4 border-b text-center">{{ $item->size ?? 'N/A' }}</td>
                                <td class="py-3 px-4 border-b text-center">{{ $item->quantity }}</td>
                                <td class="py-3 px-4 border-b text-right font-semibold">
                                    ₱{{ number_format($item->price * $item->quantity, 2) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="flex justify-between mt-4 font-bold text-lg">
                <span>Total Payment:</span>
                <span>₱{{ number_format($order->total, 2) }}</span>
            </div>
        </div>

        <!-- Customer Info (No changes needed here) -->
        <div class="bg-white p-6 rounded-lg shadow mt-4">
            <h2 class="text-lg font-semibold mb-4">Customer Information</h2>
            <div class="space-y-2 text-gray-700">
                <div>
                    <span class="font-medium">Name: </span> {{ $order->delivery_name ?? 'N/A' }}
                </div>
                <div>
                    <span class="font-medium">Phone: </span> {{ $order->delivery_phone ?? 'N/A' }}
                </div>
            </div>
        </div>

        {{-- *** MODIFIED: Added a "View My Orders" button for better navigation *** --}}
        <div class="mt-6 flex flex-col sm:flex-row gap-3">
             <a href="{{ route('home') }}" class="px-6 py-3 bg-accent text-white font-semibold rounded hover:bg-accent-dark transition text-center">
                Back to Home
            </a>
             <a href="{{ route('orders.index') }}" class="px-6 py-3 bg-gray-100 text-gray-800 font-semibold rounded hover:bg-gray-200 transition text-center">
                View My Orders
            </a>
        </div>
    </div>

    <!-- Right: E-Receipt -->
    <div class="w-full lg:w-96 bg-white rounded-lg shadow p-6 h-fit mt-6 lg:mt-16">
        <div class="text-center border-b pb-4 mb-4">
            <img src="{{ asset('image/easeP.jpg') }}" alt="Ease Print Logo" class="w-16 mx-auto mb-2">
            {{-- *** MODIFIED: Changed title to be more accurate for this stage *** --}}
            <h2 class="text-xl font-bold">Order Details</h2>
            <p class="text-sm text-gray-500">Awaiting Payment Verification</p>
        </div>

        <div class="space-y-2 text-sm text-gray-700">
            <div class="flex justify-between">
                <span>Receipt No.</span>
                <span class="font-medium">#{{ $order->order_id }}</span>
            </div>
            <div class="flex justify-between">
                <span>Date</span>
                <span>{{ $order->created_at->format('M d, Y h:i A') }}</span>
            </div>
            <div class="flex justify-between">
                <span>Customer</span>
                <span>{{ $order->delivery_name ?? 'N/A' }}</span>
            </div>
            <div class="flex justify-between">
                <span>Payment Method</span>
                {{-- *** MODIFIED: Cleans up the payment method display *** --}}
                <span class="font-medium">
                    @if($order->payment_method === 'gcash_manual')
                        GCash
                    @else
                        {{ ucfirst($order->payment_method) }}
                    @endif
                </span>
            </div>
        </div>

        <div class="border-t mt-4 pt-4">
            <h3 class="font-semibold mb-2">Items</h3>
            @foreach ($order->items as $item)
                <div class="flex justify-between text-sm">
                    <span>{{ $item->product->name }} × {{ $item->quantity }}</span>
                    <span>₱{{ number_format($item->price * $item->quantity, 2) }}</span>
                </div>
            @endforeach
        </div>

        <div class="border-t mt-4 pt-4 flex justify-between font-bold text-lg">
            <span>Total</span>
            <span>₱{{ number_format($order->total, 2) }}</span>
        </div>

        <a href="{{ route('orders.receipt.pdf', $order->id) }}" 
           class="w-full mt-4 bg-gray-800 text-white py-2 rounded hover:bg-gray-900 transition text-center block">
           Download PDF
        </a>
    </div>

</div>

@endsection