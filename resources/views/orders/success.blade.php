@extends('layouts.app')

@php
    $hideHero = true;
@endphp

@section('contents')

<div class="flex flex-col items-center gap-4 mt-10">
    <h1 class="text-3xl font-bold text-green-600">Order Successful!</h1>

    <p class="text-gray-700 text-lg">
        Thank you, {{ $order->delivery_name ?? 'Customer' }}. Your order has been placed successfully.
    </p>
    <p class="text-gray-500">
        Order ID: <span class="font-semibold">{{ $order->id }}</span>
    </p>

    <!-- Products Ordered -->
    <div class="bg-white p-6 rounded-lg shadow w-full lg:w-2/3 mt-6">
        <h2 class="text-xl font-semibold mb-4">Products Ordered</h2>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="text-left py-2 px-4">Product</th>
                        <th class="text-center py-2 px-4">Size</th>
                        <th class="text-center py-2 px-4">Quantity</th>
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

        <!-- Order Total -->
        <div class="flex justify-between mt-4 font-bold text-lg">
            <span>Total Payment:</span>
            <span>₱{{ number_format($order->total, 2) }}</span>
        </div>
    </div>

    <!-- Customer Info -->
    <div class="bg-white p-6 rounded-lg shadow w-full lg:w-2/3 mt-4">
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

    <a href="{{ route('home') }}" class="mt-6 px-6 py-3 bg-accent text-white font-semibold rounded hover:bg-accent-dark transition">
        Back to Home
    </a>
</div>

@endsection
