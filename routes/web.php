<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReviewController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomShirtController;
use App\Models\Product;
use App\Http\Controllers\AppointmentWebController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\Auth\MFAController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\SocialLoginController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

// Home and product routes
Route::get('/', [ProductController::class, 'index'])->name('home');
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');

Route::post('/products/{product}/reviews', [ReviewController::class, 'store'])
    ->name('reviews.store')
    ->middleware(['auth', 'throttle:reviews']);

// Auth routes
require __DIR__ . '/auth.php';

// ✅ Secure Logout Route (important for session reset)
Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/login');
})->name('logout');

// Authenticated routes (no prevent-back-history)
Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

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

    // Chat
    Route::get('/chat', function () {
        return view('chat.chat');
    })->name('chat');

    // Custom Shirt routes
    Route::get('/custom-shirt/request', [CustomShirtController::class, 'create'])->name('custom-shirt.create');
    Route::post('/custom-shirt/request', [CustomShirtController::class, 'store'])->name('custom-shirt.store');
    Route::get('/custom-shirt/my-requests', [CustomShirtController::class, 'myRequests'])->name('custom-shirt.my-requests');
});

// About
Route::get('/about', function () {
    return view('about'); // about.blade.php in resources/views
})->name('about');

// Customer-facing form (Laravel page)
Route::get('/appointments/create', [AppointmentWebController::class, 'create'])->name('appointments.create');
Route::post('/appointments', [AppointmentWebController::class, 'store'])->name('appointments.store');
Route::get('/appointments/success', [AppointmentWebController::class, 'success'])->name('appointments.success');

// MFA Routes
Route::get('/mfa/verify', [MFAController::class, 'showForm'])->name('mfa.form');
Route::post('/mfa/verify', [MFAController::class, 'verify'])->name('mfa.verify');

// Social Login routes
Route::get('auth/google/redirect', [SocialLoginController::class, 'redirectToGoogle'])->name('auth.google.redirect');
Route::get('auth/google/callback', [SocialLoginController::class, 'handleGoogleCallback'])->name('auth.google.callback');

Route::get('auth/facebook/redirect', [SocialLoginController::class, 'redirectToFacebook'])->name('auth.facebook.redirect');
Route::get('auth/facebook/callback', [SocialLoginController::class, 'handleFacebookCallback'])->name('auth.facebook.callback');

// Shop routes
Route::get('/shop', [ShopController::class, 'index'])->name('shop.index');
Route::get('/shop/category/{id}', [ShopController::class, 'show'])->name('shop.category');

Route::get('/auth/status', function (Request $request) {
    return response()->json(['authenticated' => (bool) $request->user()]);
})->name('auth.status');