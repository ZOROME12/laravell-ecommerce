<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    // Show all categories
    public function index()
    {
        $categories = Category::all();
        // this will load: resources/views/shop/shop.blade.php
        return view('shop.shop', compact('categories'));
    }

    // Show products under a category
    public function show($id)
    {
        $category = Category::findOrFail($id);
        $products = $category->products; // assumes Category has products() relation

        // you will need to create resources/views/shop/show.blade.php
        return view('shop.show', compact('category', 'products'));
    }
}
