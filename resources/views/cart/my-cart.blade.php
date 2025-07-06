@extends('layouts.app')

@php
    $hideHero = true; // This will make the variable available to the layout
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
                <p class="text-xl font-bold">Total: ${{ number_format($cartItems->sum(fn($item) => $item->quantity * $item->product->price), 2) }}</p>
                <form action="{{ route('order.store') }}" method="POST" class="mt-4">
                    @csrf
                    <button type="submit" class="btn-primary px-4 py-2 rounded">Place Order</button>
                </form>
            </div>
        </div>
    @endif
@endsection