@extends('layouts.app')

{{-- 
    1. STYLES:
    We define the "before" (hidden) state and the "after" (visible) state.
    The 'transition' property makes the change between states smooth.
--}}
@push('styles')
<style>
    /* Base invisible state for fade-up */
    .animate-fade-up {
        opacity: 0;
        transform: translateY(20px);
        transition: opacity 0.7s ease-out, transform 0.7s ease-out;
    }
    
    /* Base invisible state for fade-right */
    .animate-fade-right {
        opacity: 0;
        transform: translateX(20px);
        transition: opacity 0.7s ease-out, transform 0.7s ease-out;
    }

    /* Base invisible state for scale */
    .animate-fade-scale {
        opacity: 0;
        transform: scale(0.9);
        transition: opacity 0.5s ease-out, transform 0.5s ease-out;
    }

    /* The "visible" state that JS will add */
    .is-visible {
        opacity: 1;
        transform: none; /* Resets transform to default (e.g., translateY(0)) */
    }

    /* Floating animation */
    @keyframes float {
        0% { transform: translateY(0); }
        50% { transform: translateY(-10px); }
        100% { transform: translateY(0); }
    }

    /* Apply float effect */
    .floating-image {
        animation: float 3s ease-in-out infinite;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    /* Bump effect on hover */
    .floating-image:hover {
        transform: scale(1.05);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
    }
</style>
@endpush


@section('contents')
<div class="bg-white rounded-lg shadow-xl overflow-hidden max-w-5xl mx-auto my-8">

    <div class="grid grid-cols-1 md:grid-cols-2">

        {{-- 
            2. HTML:
            We add the new animation classes (e.g., 'animate-fade-up')
            and a 'data-delay' attribute to time the stagger.
        --}}
        <div class="p-8 md:p-12 flex flex-col justify-center">
            <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4 animate-fade-up" data-delay="100">
                Ease Print on the Go
            </h1>
            <p class="text-lg text-gray-700 mb-6 animate-fade-up" data-delay="300">
                Manage your orders, track custom requests, and receive status notifications right from your phone.
            </p>

            <div class="mt-2 animate-fade-up" data-delay="500">
                <a href="{{ asset('apk/easeprint-app.apk') }}"
                   download="easeprint-app.apk"
                   class="inline-flex items-center justify-center bg-rose-600 text-white rounded-lg px-8 py-3 text-lg font-semibold transition-all duration-300 ease-in-out hover:bg-rose-700 hover:scale-105 hover:shadow-xl">
                    <i class="fas fa-download text-xl mr-3"></i>
                    <span>Download APK</span>
                </a>
            </div>
            <p class="text-xs text-gray-500 mt-3 animate-fade-up" data-delay="600">
                For Android devices.
            </p>
        </div>

        <div class="p-8 flex justify-center items-center animate-fade-right" data-delay="500">
            <img src="/image/apk.png" 
                 alt="Ease Print App Screenshot"
                 class="inline-block rounded-2xl shadow-lg h-[400px] object-cover floating-image">
        </div>

    </div>

    <div class="border-t border-gray-200 p-8 md:p-12">
        <h2 class="text-2xl font-bold text-gray-900 text-center mb-6 animate-fade-up" data-delay="700">
            App Features
        </h2>
        
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 text-center">

            {{-- Feature 1 --}}
            <div class="animate-fade-scale" data-delay="900">
                <div class="bg-rose-100 text-rose-600 rounded-lg inline-flex p-4 mb-3">
                    <i class="fas fa-history text-3xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900">Order History</h3>
                <p class="text-sm text-gray-600">View all your past and current orders in one place.</p>
            </div>

            {{-- Feature 2 --}}
            <div class="animate-fade-scale" data-delay="1100">
                <div class="bg-rose-100 text-rose-600 rounded-lg inline-flex p-4 mb-3">
                    <i class="fas fa-bell text-3xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900">Get Notifications</h3>
                <p class="text-sm text-gray-600">Receive real-time updates on your order status.</p>
            </div>

            {{-- Feature 3 --}}
            <div class="animate-fade-scale" data-delay="1300">
                <div class="bg-rose-100 text-rose-600 rounded-lg inline-flex p-4 mb-3">
                    <i class="fas fa-tags text-3xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900">Browse Offers</h3>
                <p class="text-sm text-gray-600">Find exclusive app-only promotions and discounts.</p>
            </div>

        </div>
    </div>
</div>
@endsection

{{-- 
    3. SCRIPT:
    This JS runs when the page loads. It finds all elements with an 
    animation class and adds the 'is-visible' class after the 
    specified 'data-delay'.
--}}
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Find all elements we want to animate
        const animatedEls = document.querySelectorAll(
            '.animate-fade-up, .animate-fade-right, .animate-fade-scale'
        );

        animatedEls.forEach(el => {
            // Get the delay from the 'data-delay' attribute
            const delay = parseInt(el.dataset.delay) || 0; 
            
            // Use setTimeout to add the 'is-visible' class after the delay
            setTimeout(() => {
                el.classList.add('is-visible');
            }, delay);
        });
    });
</script>
@endpush
