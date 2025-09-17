@extends('layouts.app')
@php
    $hideHero = true;
@endphp

@section('contents')

<div class="flex justify-center gap-10 items-start mb-10">

    <!-- Custom Shirt Request (Center) -->
    <div class="w-full max-w-2xl bg-[#FBF8FB] p-8 rounded-2xl shadow-lg border border-[#FBB3C8]">
        <h2 class="text-3xl font-bold mb-6 text-[#3F1A2B] text-center">Request a Custom Shirt</h2>

        @if(session('success'))
            <div class="bg-green-100 text-green-700 p-3 rounded mb-6 text-center font-medium">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('custom-shirt.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf

            <!-- Full Name -->
            <div>
                <label class="block mb-2 font-semibold text-[#3F1A2B]">Full Name</label>
                <input type="text" name="full_name" class="w-full border border-[#ED4A69] p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#B2183A]" required>
            </div>

            <!-- Phone -->
            <div>
                <label class="block mb-2 font-semibold text-[#3F1A2B]">Cellphone No.</label>
                <input type="text" name="phone" class="w-full border border-[#ED4A69] p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#B2183A]" required>
            </div>

            <!-- Email with Note -->
            <div>
                <label class="block mb-2 font-semibold text-[#3F1A2B]">Email</label>
                <div class="bg-blue-50 border border-blue-400 text-blue-800 p-3 rounded-lg mb-2 text-sm">
                    <p><strong>Note:</strong> Please use the same email you used to login. Make sure the email is valid so we can contact you about your custom shirt request.</p>
                </div>
                <input type="email" name="email" class="w-full border border-[#ED4A69] p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#B2183A]" required>
            </div>

            <!-- Design Upload -->
            <div>
                <label class="block mb-2 font-semibold text-[#3F1A2B]">Upload Your Design (Image)</label>
                <input type="file" name="design" class="w-full border border-[#ED4A69] p-3 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-[#B2183A]" required>
            </div>

            <!-- Size -->
            <div>
                <label class="block mb-2 font-semibold text-[#3F1A2B]">Size</label>
                <select name="size" class="w-full border border-[#ED4A69] p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#B2183A]" required>
                    <option value="">Select size</option>
                    <option value="S">Small</option>
                    <option value="M">Medium</option>
                    <option value="L">Large</option>
                    <option value="XL">Extra Large</option>
                </select>
            </div>

            <!-- Quantity -->
            <div>
                <label class="block mb-2 font-semibold text-[#3F1A2B]">Quantity</label>
                <input type="number" name="quantity" min="1" class="w-full border border-[#ED4A69] p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#B2183A]" required>
            </div>

            <!-- Notes -->
            <div>
                <label class="block mb-2 font-semibold text-[#3F1A2B]">Notes (Optional)</label>
                <textarea name="notes" rows="4" class="w-full border border-[#ED4A69] p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#B2183A]"></textarea>
            </div>

            <!-- Submit Button -->
            <div class="text-center">
                <button type="submit" class="bg-[#B2183A] hover:bg-[#3F1A2B] text-white px-6 py-3 rounded-full shadow-md transition-colors duration-200">
                    Submit Request
                </button>
            </div>
        </form>
    </div>

    <!-- Schedule Appointment Section (Right) -->
    <div class="flex flex-col items-end w-80">
        <!-- Note above button -->
        <div class="bg-yellow-50 border border-yellow-400 text-yellow-800 px-4 py-2 rounded-lg text-sm shadow-sm mb-2 text-left">
            <p class="font-semibold">Note:</p>
            <p>Please schedule a fitting appointment in advance to secure your preferred time slot. Walk-ins may not always be accommodated.</p>
        </div>

        <!-- Schedule Appointment Button -->
        <a href="{{ route('appointments.create') }}" 
           class="bg-[#3F1A2B] hover:bg-[#B2183A] text-white px-6 py-3 rounded-full shadow-md transition-colors duration-200 w-full text-center">
            Schedule Fitting Appointment
        </a>
    </div>

</div>

@endsection
