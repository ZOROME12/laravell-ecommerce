@extends('layouts.app')

@php
    $hideHero = true;
@endphp

@section('contents')
<div class="max-w-5xl mx-auto bg-white rounded-lg shadow-lg overflow-hidden">

    <!-- Header -->
    <div class="bg-gradient-to-r from-[#3F1A2B] to-[#B2183A] px-6 py-4">
        <h2 class="text-xl md:text-2xl font-bold text-white">
            My Custom Shirt Requests
        </h2>
        <p class="text-pink-200 text-sm">Track the status of your shirt orders</p>
    </div>

    <div class="p-6">
        @if($orders->isEmpty())
            <div class="text-center py-10 text-gray-500">
                <p class="text-lg">You have no requests yet.</p>
            </div>
        @else
            <div class="overflow-x-auto">
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
                        @foreach($orders as $order)
                            <tr class="border-b hover:bg-gray-50 transition">
                                <td class="p-3">{{ $order->created_at->format('M d, Y') }}</td>
                                <td class="p-3 font-medium">{{ $order->size }}</td>
                                <td class="p-3">{{ $order->quantity }}</td>
                                <td class="p-3">
                                    @php
                                        $statusColors = [
                                            'Pending' => 'bg-yellow-100 text-yellow-800',
                                            'Approved' => 'bg-green-100 text-green-800',
                                            'Rejected' => 'bg-red-100 text-red-800'
                                        ];
                                    @endphp
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $statusColors[$order->status] ?? 'bg-gray-100 text-gray-800' }}">
                                        {{ $order->status }}
                                    </span>
                                </td>
                                <td class="p-3 text-gray-600">
                                    {{ $order->admin_note ?? 'No notes yet.' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection
