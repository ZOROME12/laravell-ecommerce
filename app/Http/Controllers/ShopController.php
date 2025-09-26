<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    // Show all categories with their products
    public function index()
    {
        // Eager load products for each category
        $categories = Category::with('products')->get();

        // resources/views/shop/shop.blade.php
        return view('shop.shop', compact('categories'));
    }

    // Show products under a specific category
    public function show($id)
    {
        $category = Category::with('products')->findOrFail($id);

        // resources/views/shop/show.blade.php
        return view('shop.show', compact('category'));
    }
}
