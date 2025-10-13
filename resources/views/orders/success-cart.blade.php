@extends('layouts.app')

@php
    $hideHero = true;
@endphp

@section('contents')

<div class="flex flex-col lg:flex-row justify-center gap-8 mt-10 w-full px-4 lg:px-0">

    <!-- Left: Order Summary -->
    <div class="flex-1 max-w-3xl">
        <h1 class="text-3xl font-bold text-green-600 text-center lg:text-left">Order Successful!</h1>

        <p class="text-gray-700 text-lg text-center lg:text-left mt-2">
            Thank you, {{ $order->delivery_name ?? 'Customer' }}. Your cart order has been placed successfully.
        </p>
        <p class="text-gray-500 text-center lg:text-left">
            Order ID: <span class="font-semibold">{{ $order->order_id }}</span>
        </p>

        <!-- Products Ordered -->
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
                                        <p class="text-sm text-gray-500">Size: {{ $item->size ?? 'N/A' }}</p>
                                    </div>
                                </td>
                                <td class="py-3 px-4 border-b text-center">{{ $item->size ?? 'N/A' }}</td>
                                <td class="py-3 px-4 border-b text-center">{{ $item->quantity ?? 1 }}</td>
                                <td class="py-3 px-4 border-b text-right font-semibold">
                                    ₱{{ number_format(($item->price ?? 0) * ($item->quantity ?? 1), 2) }}
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

        <!-- Customer Info -->
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

        <a href="{{ route('home') }}" class="mt-6 inline-block px-6 py-3 bg-accent text-white font-semibold rounded hover:bg-accent-dark transition">
            Back to Home
        </a>
    </div>

    <!-- Right: E-Receipt -->
    <div class="w-full lg:w-96 bg-white rounded-lg shadow p-6 h-fit mt-6 lg:mt-16">
        <div class="text-center border-b pb-4 mb-4">
            <img src="{{ asset('image/easeP.jpg') }}" alt="Ease Print Logo" class="w-16 mx-auto mb-2">
            <h2 class="text-xl font-bold">Ease Print</h2>
            <p class="text-sm text-gray-500">Official E-Receipt</p>
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
                <span>{{ ucfirst($order->payment_method) }}</span>
            </div>
        </div>

        <div class="border-t mt-4 pt-4">
            <h3 class="font-semibold mb-2">Items</h3>
            @foreach ($order->items as $item)
                <div class="flex justify-between text-sm">
                    <span>{{ $item->product->name }} × {{ $item->quantity ?? 1 }} @if($item->size) ({{ $item->size }}) @endif</span>
                    <span>₱{{ number_format(($item->price ?? 0) * ($item->quantity ?? 1), 2) }}</span>
                </div>
            @endforeach
        </div>

        <div class="border-t mt-4 pt-4 flex justify-between font-bold text-lg">
            <span>Total</span>
            <span>₱{{ number_format($order->total, 2) }}</span>
        </div>

        <button class="w-full mt-4 bg-gray-800 text-white py-2 rounded hover:bg-gray-900 transition">
            Download PDF
        </button>
    </div>

</div>

@endsection
