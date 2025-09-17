<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CustomShirt;
use Illuminate\Support\Facades\Auth;

class CustomShirtController extends Controller
{
    // Show the form for requesting a custom shirt
    public function create()
    {
        return view('custom-shirt.create');
    }

    // Store a new custom shirt request
    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
            'email' => 'required|email',
            'design' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'size' => 'required|string',
            'quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string|max:1000',
        ]);

        // Upload design image if exists
        if ($request->hasFile('design')) {
            $validated['design'] = $request->file('design')->store('designs', 'public');
        }

        // Default values
        $validated['status'] = 'Pending';
        $validated['admin_note'] = null;

        // Save to DB
        CustomShirt::create($validated);

        return redirect()->route('custom-shirt.create')->with('success', 'Your request has been submitted!');
    }

    // Show all requests for the logged-in customer
    public function myRequests()
    {
        $orders = CustomShirt::where('email', Auth::user()->email)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('custom-shirt.my-requests', compact('orders'));
    }
}
