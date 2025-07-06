@extends('layouts.app')

@section('contents')
   <h1 class="text-3xl font-bold mb-6 text-[#3F1A2B]">Our Products</h1>
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
        @foreach ($products as $product)
            <div class="bg-white shadow-md overflow-hidden border border-[#FBB3C8] h-[470px]" style="border-radius: 14px;">
                <a href="{{ route('products.show', $product) }}">
                    <img src="{{ Storage::url($product->image ?? 'products/placeholder.jpg') }}" alt="{{ $product->name }}" class="w-full h-[240px] object-cover bg-[#FBF8FB] hover:opacity-90 transition">
                </a>
                <div class="p-4 flex flex-col justify-between h-[calc(100%-240px)]">
                    <div>
                        <a href="{{ route('products.show', $product) }}" class="hover:underline">   
                            <h3 class="text-[16px] font-bold text-[#3F1A2B]">{{ $product->name }}</h3> </a>
                        <div class="flex items-center space-x-2">
                            <span class="text-[14px] font-bold text-[#B2183A]">₱{{ number_format($product->price, 2) }}</span>
                            @if($product->original_price)
                                <span class="text-[14px] line-through text-gray-400">₱{{ number_format($product->original_price, 2) }}</span>
                            @endif
                        </div>
                        <p class="text-gray-600 my-3 text-sm">{{ Str::limit($product->description, 100) }}</p>
                        <p class="text-[12px] mt-2 text-[#3F1A2B]">Sizes:</p>
                        <div class="flex space-x-2 mt-2 mb-4">
                            <span class="w-8 h-8 flex items-center justify-center border border-[#ED4A69] rounded-full text-[12px] font-bold shadow-md cursor-pointer hover:bg-[#FBB3C8] transition">S</span>
                            <span class="w-8 h-8 flex items-center justify-center border border-[#ED4A69] rounded-full text-[12px] font-bold shadow-md cursor-pointer hover:bg-[#FBB3C8] transition">M</span>
                            <span class="w-8 h-8 flex items-center justify-center border border-[#ED4A69] rounded-full text-[12px] font-bold shadow-md cursor-pointer hover:bg-[#FBB3C8] transition">L</span>
                            <span class="w-8 h-8 flex items-center justify-center border border-[#ED4A69] rounded-full text-[12px] font-bold shadow-md cursor-pointer hover:bg-[#FBB3C8] transition">XL</span>
                        </div>
                    </div>
                    @auth
                        <form action="{{ route('cart.add', $product) }}" method="POST" class="flex space-x-2">
                            @csrf
                            <button type="submit" class="flex-1 bg-[#3F1A2B] text-white text-[12px] py-2 rounded-[20px] hover:brightness-110 transition-all">
                                Add to Cart
                            </button>
                            <a href="{{ route('order.placeSingle', $product) }}" class="flex-1 text-center bg-[#B2183A] text-white text-[12px] py-2 rounded-[20px] hover:opacity-90 transition-all">
                               Order Now
                            </a>
                        </form>
                    @else
                        <div class="flex space-x-2">
                            <button onclick="openModal('login-modal')" class="flex-1 bg-[#3F1A2B] text-white text-[12px] py-2 rounded-[20px] hover:brightness-110 transition-all">
                                Login to Add
                            </button>
                            <button onclick="openModal('login-modal')" class="flex-1 bg-[#B2183A] text-white text-[12px] py-2 rounded-[20px] hover:opacity-90 transition-all">
                                Login to Order
                            </button>
                        </div>
                    @endauth
                </div>
            </div>
        @endforeach
    </div>
@endsection