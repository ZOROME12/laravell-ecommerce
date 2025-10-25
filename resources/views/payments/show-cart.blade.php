@extends('layouts.app') {{-- Use your main layout file --}}

@php
    // Add any variables needed for your layout, like $hideHero
    $hideHero = true;
@endphp

@section('contents') {{-- Use the correct section name for your layout --}}

<div class="max-w-2xl mx-auto py-8 px-4 sm:px-6 lg:px-8">

    {{-- Session Status/Error Messages --}}
    @if (session('status'))
        <div class="bg-green-100 text-green-800 p-4 rounded mb-6 text-sm">
            {{ session('status') }}
        </div>
    @endif
    @if (session('error'))
        <div class="bg-red-100 text-red-800 p-4 rounded mb-6 text-sm">
            {{ session('error') }}
        </div>
    @endif

    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-6">Complete Your Payment</h1>

    <div class="bg-white p-6 rounded-lg shadow-md space-y-6">

        {{-- Order Summary --}}
        <div>
            <h2 class="text-lg font-semibold mb-2 text-gray-800">Order Summary</h2>
            <div class="text-sm text-gray-600 space-y-1">
                <p><strong>Order ID:</strong> {{ $order->order_id }}</p>
                <p><strong>Total Amount Due:</strong> <strong class="text-accent text-lg">₱{{ number_format($order->total, 2) }}</strong></p>
                {{-- You can add more order details here if needed --}}
                {{-- Example: List items --}}
                {{-- <p><strong>Items:</strong>
                    @foreach($order->items as $item)
                        {{ $item->product->name }} (x{{ $item->quantity }}){{ !$loop->last ? ',' : '' }}
                    @endforeach
                </p> --}}
            </div>
        </div>

        <hr>

        {{-- Payment Instructions --}}
        <div>
            <h2 class="text-lg font-semibold mb-3 text-gray-800">Step 1: Pay via GCash</h2>
            <div class="flex flex-col sm:flex-row items-center gap-4 bg-gray-50 p-4 rounded border">
                {{-- QR Code Image --}}
                <div class="flex-shrink-0">
                    {{-- *** IMPORTANT: Replace 'images/gcash_qr_code.png' with the actual path to your QR code image in the public folder *** --}}
                 <img src="{{ $qrPath ? Storage::url($qrPath) : asset('image/EASEQR.jpg') }}" alt="GCash QR Code" class="w-64 h-64 border rounded mx-auto">
                    {{-- Make sure you have uploaded your GCash QR code image to your project's public/images folder --}}
                </div>
                {{-- Instructions Text --}}
                <div class="text-sm text-gray-700 space-y-2 text-center sm:text-left">
                    <p>Scan the QR code using your GCash app.</p>
                    <p>Ensure you pay the <strong class="text-accent">exact amount</strong>: <strong class="text-accent">₱{{ number_format($order->total, 2) }}</strong>.</p>
                    <p>After payment, take a <strong class="text-accent">screenshot</strong> of the successful transaction. You'll need the screenshot and the <strong class="text-accent">GCash Reference Number</strong> for the next step.</p>
                     {{-- Optional: Add your GCash name/number here for manual lookup reassurance --}}
                    {{-- <p class="text-xs text-gray-500 mt-2">GCash Name: Your Name / Number: 09xxxxxxxxx</p> --}}
                </div>
            </div>
        </div>

        <hr>

        {{-- Payment Confirmation Form --}}
        <div>
            <h2 class="text-lg font-semibold mb-4 text-gray-800">Step 2: Confirm Your Payment</h2>

            {{-- *** This form points to the payment.confirm route *** --}}
           <form action="{{ route('payment.confirmCart', $order) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf {{-- CSRF Protection --}}

                {{-- Reference Number Input --}}
                <div>
                    <label for="payment_reference_no" class="block text-sm font-medium text-gray-700 mb-1">GCash Reference No.<span class="text-red-600">*</span></label>
                    <input type="text" id="payment_reference_no" name="payment_reference_no"
                           placeholder="Enter the 13-digit number from your GCash receipt"
                           required
                           value="{{ old('payment_reference_no') }}"
                           class="w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:ring-accent focus:border-accent">
                    @error('payment_reference_no')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Screenshot Upload Input --}}
                <div>
                    <label for="payment_screenshot" class="block text-sm font-medium text-gray-700 mb-1">Upload Payment Screenshot<span class="text-red-600">*</span></label>
                    <input type="file" id="payment_screenshot" name="payment_screenshot"
                           required
                           accept="image/jpeg,image/png,image/gif,image/webp" {{-- Specify accepted types --}}
                           class="block w-full text-sm text-gray-500 border border-gray-300 rounded-md cursor-pointer
                                  file:mr-4 file:py-2 file:px-4
                                  file:rounded-l-md file:border-0
                                  file:text-sm file:font-semibold
                                  file:bg-accent-light file:text-accent
                                  hover:file:bg-accent-lighter">
                     <p class="text-xs text-gray-500 mt-1">Allowed types: JPG, PNG, GIF, WEBP. Max size: 5MB.</p>
                    @error('payment_screenshot')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Submit Button --}}
                <div class="pt-2">
                    <button type="submit" class="w-full bg-accent hover:bg-accent-dark text-white py-3 px-4 rounded-md font-semibold transition duration-150 ease-in-out flex items-center justify-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                        Submit Payment Confirmation
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection