<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\Admin\ProductController;


// Admin login route
Route::post('/admin/login', [AdminAuthController::class, 'login']);

// Routes for admin (requires admin token)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/admin/products', function (Request $request) {
        Auth::shouldUse('admin-api'); // 👈 force admin guard
        return app(ProductController::class)->store($request);
    });

    Route::get('/admin/products', function (Request $request) {
        Auth::shouldUse('admin-api');
        return app(ProductController::class)->index($request);
    });

    Route::put('/admin/products/{id}', function (Request $request, $id) {
        Auth::shouldUse('admin-api');
        return app(ProductController::class)->update($request, $id);
    });

    Route::delete('/admin/products/{id}', function (Request $request, $id) {
        Auth::shouldUse('admin-api');
        return app(ProductController::class)->destroy($id);
    });
});


// Regular user login & authenticated user route
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->get('/user', [AuthController::class, 'user']);
