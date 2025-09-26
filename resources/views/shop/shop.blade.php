@extends('layouts.app')

@php
    $hideHero = true;
@endphp

@section('contents')
    <h1 class="text-3xl font-bold mb-6">Shop Categories</h1>

    @if($categories->isEmpty())
        <p>No categories available.</p>
    @else
        <div class="grid sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach($categories as $category)
                <a href="{{ route('shop.show', $category->id) }}" 
                   class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition">
                    <h2 class="text-xl font-semibold text-[var(--accent)] mb-2">{{ $category->name }}</h2>
                    <p class="text-gray-600 text-sm">
                        {{ $category->description ?? 'Browse products in this category' }}
                    </p>
                </a>
            @endforeach
        </div>
    @endif
@endsection
