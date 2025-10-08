@extends('layouts.app')

@php
    $hideHero = true;
@endphp

@section('contents')
    <h1 class="text-3xl font-bold mb-6">Your Cart</h1>
    @if (session('success'))
        <div class="bg-green-100 text-green-800 p-4 rounded mb-6">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="bg-red-100 text-red-800 p-4 rounded mb-6">{{ session('error') }}</div>
    @endif
    @if ($cartItems->isEmpty())
        <p>Your cart is empty.</p>
    @else
        <div class="bg-white shadow rounded-lg p-4">
            @foreach ($cartItems as $item)
                <div class="flex items-center justify-between border-b py-4">
                    <div class="flex items-center">
                        <img src="{{ $item->product->image ? Storage::url($item->product->image) : 'https://via.placeholder.com/50' }}" 
                            alt="{{ $item->product->name }}" 
                            class="w-16 h-16 object-cover rounded mr-4">
                        <div>
                            <h2 class="text-lg font-semibold">{{ $item->product->name }}</h2>
                            <p class="text-gray-600">Quantity: {{ $item->quantity }}</p>
                            <p class="text-[var(--secondary)] font-bold">${{ number_format($item->product->price * $item->quantity, 2) }}</p>
                        </div>
                    </div>
                    <form action="{{ route('cart.remove', $item) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-[var(--accent)]">Remove</button>
                    </form>
                </div>
            @endforeach
           <div class="mt-4">
                <p class="text-xl font-bold">
                    Total: ${{ number_format($cartItems->sum(fn($item) => $item->quantity * $item->product->price), 2) }}
                </p>
               <a href="{{ route('order.place') }}" 
                class="inline-block mt-4 px-6 py-3 rounded-lg font-semibold text-white 
                        bg-red-600 hover:bg-red-700 
                        shadow-md hover:shadow-lg hover:scale-105 
                        transition duration-300 ease-in-out">
                        Place Order
                </a>

            </div>
        </div>
    @endif
@endsection