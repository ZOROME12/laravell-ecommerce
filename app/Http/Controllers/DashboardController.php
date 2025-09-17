<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // Only take 3 latest products
        $products = Product::latest()->take(3)->get();

        return view('dashboard', compact('products'));
    }
}
