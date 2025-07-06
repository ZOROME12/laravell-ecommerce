<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class ReviewController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('auth'),
        ];
    }

    public function store(Request $request, Product $product)
    {
        $validated = $request->validate([
            'rating' => 'required|integer|between:1,5',
            'comment' => 'nullable|string|max:1000',
        ]);

        // Check if user already reviewed this product
        if (Auth::user()->reviews()->where('product_id', $product->id)->exists()) {
            return back()->with('error', 'You have already reviewed this product!');
        }

        // Create the review
        $review = new Review();
        $review->product_id = $product->id;
        $review->user_id = Auth::id();
        $review->rating = $validated['rating'];
        $review->comment = $validated['comment'];
        $review->save();

        return back()->with('success', 'Thank you for your review!');
    }
}
