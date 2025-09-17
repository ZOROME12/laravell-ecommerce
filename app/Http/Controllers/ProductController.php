<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\Category;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::withCount('reviews')
            ->withAvg('reviews', 'rating')
            ->get();

        return view('products.index', compact('products'));
    }


    public function show(Product $product)
    {
        $product->load(['reviews.user'])->loadCount('reviews');
        return view('products.show', compact('product'));
    }
}
