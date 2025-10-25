@extends('layouts.app')

@php
    $hideHero = true;
@endphp

@section('contents')
<link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">

<style>
/* Simple, clean, interactive hover effects */

/* Card lift */
.hover-card {
    transition: transform 0.22s cubic-bezier(.2,.9,.2,1), box-shadow 0.22s cubic-bezier(.2,.9,.2,1);
    transform: translateY(0) scale(1);
}
.hover-card:hover {
    transform: translateY(-6px) scale(1.02);
    box-shadow: 0 10px 30px rgba(18, 18, 18, 0.08);
}

/* Icon subtle lift */
.hover-card i {
    transition: transform 0.18s ease, color 0.18s ease;
}
.hover-card:hover i {
    transform: translateY(-3px);
}

/* Image scale */
.hover-img {
    transition: transform 0.45s cubic-bezier(.2,.9,.2,1);
    will-change: transform;
}
.hover-card:hover .hover-img {
    transform: scale(1.04);
}

/* Buttons and links */
button.hover-btn, a.hover-btn {
    transition: transform 0.16s ease, box-shadow 0.16s ease;
}
button.hover-btn:hover, a.hover-btn:hover {
    transform: translateY(-3px) scale(1.01);
    box-shadow: 0 6px 18px rgba(18,18,18,0.06);
}

/* Table row hover (modal) */
.modal-content tbody tr:hover {
    background-color: #fbfbfb;
    transition: background-color 0.18s ease;
}
</style>

<div class="bg-[#FBF8FB] py-10">
    <div class="max-w-7xl mx-auto px-6 space-y-12">

        <div data-aos="fade-down" class="hover-card flex flex-col sm:flex-row items-center justify-between bg-white rounded-xl shadow-lg p-8 border-t-4 border-[#B2183A]">
            <div>
                <h1 class="text-3xl font-bold text-[#3F1A2B]">Welcome, {{ Auth::user()->name }}!</h1>
                <p class="mt-2 text-gray-600 text-sm">Your EASEPrint Dashboard</p>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-8">
            <div data-aos="zoom-in" data-aos-delay="100" class="hover-card bg-white rounded-xl shadow-lg p-6 flex items-center border-t-4 border-[#B2183A]">
                <div class="bg-[#B2183A] text-white p-4 rounded-full mr-5">
                    <i class="fas fa-box-open text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Orders</p>
                    <p class="text-2xl font-bold text-[#3F1A2B]">{{ $orderCount ?? 0 }}</p>
                </div>
            </div>
            <div data-aos="zoom-in" data-aos-delay="200" class="hover-card bg-white rounded-xl shadow-lg p-6 flex items-center border-t-4 border-[#ED4A69]">
                <div class="bg-[#ED4A69] text-white p-4 rounded-full mr-5">
                    <i class="fas fa-shopping-cart text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Cart Items</p>
                    <p class="text-2xl font-bold text-[#3F1A2B]">{{ $cartItemCount ?? 0 }}</p>
                </div>
            </div>
        </div>

        <div data-aos="fade-up">
            <h2 class="text-xl font-bold text-[#3F1A2B] mb-6">Quick Actions</h2>
            
            @php
                $isAdmin = Auth::user() && Auth::user()->is_admin;
                $quickActionCols = $isAdmin ? 'sm:grid-cols-4' : 'sm:grid-cols-3';
            @endphp
            <div class="grid grid-cols-2 {{ $quickActionCols }} gap-8">
                
                <a data-aos="zoom-in" data-aos-delay="100" href="{{ route('products.index') }}" class="hover-card hover-btn bg-white rounded-xl shadow-lg p-6 text-center border-t-4 border-[#B2183A]">
                    <i class="fas fa-boxes text-2xl text-[#B2183A] mb-2"></i>
                    <p class="text-sm font-semibold text-[#3F1A2B]">Browse Products</p>
                </a>

                <button data-aos="zoom-in" data-aos-delay="200" onclick="openModal('orders-modal')" class="hover-card hover-btn bg-white rounded-xl shadow-lg p-6 text-center border-t-4 border-[#ED4A69]">
                    <i class="fas fa-clipboard-list text-2xl text-[#ED4A69] mb-2"></i>
                    <p class="text-sm font-semibold text-[#3F1A2B]">My Orders</p>
                </button>
                
                <button data-aos="zoom-in" data-aos-delay="300" onclick="openModal('requests-modal')" class="hover-card hover-btn bg-white rounded-xl shadow-lg p-6 text-center border-t-4 border-[#3F1A2B]">
                    <i class="fas fa-paint-brush text-2xl text-[#3F1A2B] mb-2"></i>
                    <p class="text-sm font-semibold text-[#3F1A2B]">My Requests</p>
                </button>
                
                @if($isAdmin)
                <a data-aos="zoom-in" data-aos-delay="400" href="{{ route('admin.dashboard') }}" class="hover-card hover-btn bg-white rounded-xl shadow-lg p-6 text-center border-t-4 border-red-700">
                    <i class="fas fa-shield-alt text-2xl text-red-700 mb-2"></i>
                    <p class="text-sm font-semibold text-[#3F1A2B]">Admin Panel</p>
                </a>
                @endif
            </div>
        </div>

        <div data-aos="fade-up">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-bold text-[#3F1A2B]">Recommended For You</h2>
                <a href="{{ route('products.index') }}" class="text-sm text-[#ED4A69] hover:underline">View All</a>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse($products as $index => $product)
                <div data-aos="fade-up" data-aos-delay="{{ ($index % 3) * 100 + 100 }}" class="hover-card bg-white rounded-xl shadow-lg border-t-4 border-[#B2183A] flex flex-col justify-between">
                    <div>
                        <a href="{{ route('products.show', $product) }}">
                            <div class="relative overflow-hidden rounded-t-xl">
                                <img src="{{ Storage::url($product->image ?? 'products/placeholder.jpg') }}" alt="{{ $product->name }}" class="w-full h-48 object-cover hover-img">
                            </div>
                        </a>
                        <div class="p-5">
                            <a href="{{ route('products.show', $product) }}">
                                <h3 class="text-lg font-semibold text-[#3F1A2B]">{{ $product->name }}</h3>
                                <p class="text-sm text-gray-600 mb-3">{{ Str::limit($product->description, 40) }}</p>
                            </a>
                        </div>
                    </div>
                    
                    <div class="p-5 pt-0">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-lg font-bold text-[#B2183A]">₱{{ number_format($product->price, 2) }}</span>
                        </div>

                        @auth
                        <form action="{{ route('cart.add', $product) }}" method="POST" class="add-to-cart-form">
                            @csrf
                            <input type="hidden" name="size" class="selected-size-input" required>
                            
                            <div class="flex space-x-2 mb-3">
                                @foreach (['S', 'M', 'L', 'XL'] as $size)
                                    <button 
                                        type="button" 
                                        class="size-option w-7 h-7 flex items-center justify-center border border-[#ED4A69] rounded-full text-[10px] font-bold cursor-pointer hover:bg-[#FBB3C8] transition"
                                        data-size="{{ $size }}">
                                        {{ $size }}
                                    </button>
                                @endforeach
                            </div>
                            
                            <button type="submit" class="w-full bg-[#ED4A69] text-white px-4 py-2 rounded-full text-sm hover:bg-[#B21A3A] transition hover-btn">
                                <i class="fas fa-cart-plus mr-1"></i> Add
                            </button>
                        </form>
                        @else
                        <button onclick="openModal('login-modal')" class="w-full bg-[#ED4A69] text-white px-4 py-2 rounded-full text-sm hover:bg-[#B21A3A] transition hover-btn">
                            <i class="fas fa-cart-plus mr-1"></i> Add
                        </button>
                        @endauth
                    </div>
                </div>
                @empty
                <p class="text-gray-500">No products available right now.</p>
                @endforelse
            </div>
        </div>

    </div>
</div>

<div id="orders-modal" class="modal">
    <div class="modal-content p-6 sm:p-8 rounded-xl shadow-lg bg-white max-w-3xl w-full">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-primary">My Recent Orders</h2>
            <button onclick="closeModal('orders-modal')" class="text-accent hover:text-secondary text-2xl font-bold">
                &times;
            </button>
        </div>

        <div class="space-y-4 max-h-[60vh] overflow-y-auto pr-2">
            @forelse($userOrders as $order)
                <div class="p-4 border rounded-lg flex flex-col sm:flex-row justify-between sm:items-center">
                    <div>
                        <span class="font-bold text-primary">Order #{{ $order->id }}</span>
                        <span class="ml-2 px-2 py-0.5 rounded-full text-xs
                            @if($order->status == 'pending') bg-yellow-100 text-yellow-800
                            @elseif($order->status == 'completed') bg-green-100 text-green-800
                            @elseif($order->status == 'shipped') bg-blue-100 text-blue-800
                            @elseif($order->status == 'cancelled') bg-red-100 text-red-800
                            @else bg-gray-100 text-gray-800
                            @endif">
                            {{ ucfirst($order->status) }}
                        </span>
                        <p class="text-sm text-gray-600 mt-1">
                            Placed on: {{ $order->created_at->format('M d, Y') }}
                        </p>
                    </div>
                    <div class="text-left sm:text-right mt-2 sm:mt-0">
                        <span class="font-bold text-lg text-secondary">₱{{ number_format($order->total, 2) }}</span>
                    </div>
                </div>
            @empty
                <p class="text-gray-500 text-center py-4">You have not placed any orders yet.</p>
            @endforelse
        </div>
    </div>
</div>

<div id="requests-modal" class="modal">
    <div class="modal-content p-6 sm:p-8 rounded-xl shadow-lg bg-white max-w-3xl w-full">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-primary">My Custom Shirt Requests</h2>
            <button onclick="closeModal('requests-modal')" class="text-accent hover:text-secondary text-2xl font-bold">
                &times;
            </button>
        </div>

        <div>
            @if($customRequests->isEmpty())
                <div class="text-center py-10 text-gray-500">
                    <p class="text-lg">You have no custom requests yet.</p>
                </div>
            @else
                <div class="overflow-x-auto max-h-[60vh]">
                    <table class="w-full border-collapse">
                        <thead>
                            <tr class="bg-gray-100 text-gray-700 text-sm uppercase tracking-wider">
                                <th class="p-3 text-left">Date</th>
                                <th class="p-3 text-left">Size</th>
                                <th class="p-3 text-left">Quantity</th>
                                <th class="p-3 text-left">Status</th>
                                <th class="p-3 text-left">Admin Note</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($customRequests as $request)
                                <tr class="border-b transition">
                                    <td class="p-3">{{ $request->created_at->format('M d, Y') }}</td>
                                    <td class="p-3 font-medium">{{ $request->size }}</td>
                                    <td class="p-3">{{ $request->quantity }}</td>
                                    <td class="p-3">
                                        @php
                                            $statusColors = [
                                                'Pending' => 'bg-yellow-100 text-yellow-800',
                                                'Approved' => 'bg-green-100 text-green-800',
                                                'Rejected' => 'bg-red-100 text-red-800'
                                            ];
                                        @endphp
                                        <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $statusColors[$request->status] ?? 'bg-gray-100 text-gray-800' }}">
                                            {{ $request->status }}
                                        </span>
                                    </td>
                                    <td class="p-3 text-gray-600">
                                        {{ $request->admin_note ?? 'No notes yet.' }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
<script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
<script>
    AOS.init({
        duration: 800,
        once: false
    });

    // JavaScript for size selection
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('form.add-to-cart-form').forEach(form => {
            const sizeButtons = form.querySelectorAll('.size-option');
            const sizeInput = form.querySelector('.selected-size-input');
            
            if (!sizeButtons.length || !sizeInput) return;

            sizeButtons.forEach(button => {
                button.addEventListener('click', e => {
                    e.preventDefault();
                    sizeButtons.forEach(b => b.classList.remove('bg-[#B2183A]', 'text-white'));
                    button.classList.add('bg-[#B2183A]', 'text-white');
                    sizeInput.value = button.dataset.size;
                });
            });

            form.addEventListener('submit', e => {
                if (!sizeInput.value) {
                    e.preventDefault();
                    alert('Please select a size before adding to cart.');
                }
            });
        });
    });
</script>

@endsection