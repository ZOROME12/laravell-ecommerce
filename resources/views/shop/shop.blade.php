@extends('layouts.app')

@php
    $hideHero = true;
@endphp

@section('contents')
<div class="max-w-7xl mx-auto px-6 py-12">
    <h1 class="text-4xl font-bold text-center mb-12">Shop</h1>

    @foreach ($categories as $category)
        <section class="mb-16">
            <h2 class="text-2xl font-semibold text-gray-800 mb-6 border-b-2 border-gray-200 pb-2">
                {{ $category->name }}
            </h2>

            @if ($category->products->isEmpty())
                <p class="text-gray-500">No products available in this category yet.</p>
            @else
                <div class="grid gap-6 grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
                    @foreach ($category->products as $product)
                        <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition p-4">
                            <img src="{{ $product->image ? Storage::url($product->image) : 'https://via.placeholder.com/300' }}" 
                                 alt="{{ $product->name }}" 
                                 class="w-full h-48 object-cover rounded">
                            <h3 class="mt-4 text-lg font-bold text-gray-800">{{ $product->name }}</h3>
                            <p class="text-gray-600 text-sm mb-2">{{ $product->description }}</p>
                            <p class="text-[var(--secondary)] font-bold mb-4">${{ number_format($product->price, 2) }}</p>
                            <button class="w-full py-2 bg-[var(--accent)] text-white rounded-lg hover:bg-red-700 transition">
                                Add to Cart
                            </button>
                        </div>
                    @endforeach
                </div>
            @endif
        </section>
    @endforeach
</div>
@endsection
