<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReviewController;
use Illuminate\Support\Facades\Route;

// Home and product routes
Route::get('/', [ProductController::class, 'index'])->name('home');
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');

Route::post('/products/{product}/reviews', [ReviewController::class, 'store'])
    ->name('reviews.store')
    ->middleware(['auth', 'throttle:reviews']);

// Auth routes
require __DIR__ . '/auth.php';

// Authenticated routes
Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Profile
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Cart
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{product}', [CartController::class, 'store'])->name('cart.add');
    Route::delete('/cart/remove/{cartItem}', [CartController::class, 'destroy'])->name('cart.remove');

    // Orders - full cart
    Route::get('/order/place', [OrderController::class, 'place'])->name('order.place');
    Route::post('/order/store', [OrderController::class, 'store'])->name('order.store');

    // Orders - single product
    Route::get('/order/place/{product}', [OrderController::class, 'placeSingle'])->name('order.placeSingle');
    Route::post('/order/store-single', [OrderController::class, 'storeSingle'])->name('order.storeSingle');

    // Orders list
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
});
