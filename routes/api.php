<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\ProductController; // Shop side
use App\Http\Controllers\Admin\ProductController as AdminProductController; // Admin side
use App\Http\Controllers\MessageController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\Api\CustomShirtApiController;
use App\Http\Controllers\Api\AppointmentController;
use App\Http\Controllers\OrderController;

// ------------------ AUTH ------------------ //
Route::post('/login', [AuthController::class, 'login']);
Route::post('/admin/login', [AdminAuthController::class, 'login']);
Route::middleware('auth:sanctum')->get('/user', [AuthController::class, 'user']);

// ------------------ PUBLIC SHOP ------------------ //
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{product}', [ProductController::class, 'show']);

// ------------------ ADMIN (protected) ------------------ //
Route::middleware('auth:sanctum')->group(function () {
    // Products
    Route::post('/admin/products', [AdminProductController::class, 'store']);
    Route::get('/admin/products', [AdminProductController::class, 'index']);
    Route::put('/admin/products/{id}', [AdminProductController::class, 'update']);
    Route::delete('/admin/products/{id}', [AdminProductController::class, 'destroy']);

    // Orders
    Route::get('/admin/orders', [OrderController::class, 'apiIndex']);
    Route::put('/admin/orders/{id}/status', [OrderController::class, 'updateStatus']);
    Route::delete('/admin/orders/{id}', [OrderController::class, 'destroy']);
});

// ------------------ MESSAGES ------------------ //
Route::get('/messages/{userId}', [MessageController::class, 'fetch']);
Route::post('/messages', [MessageController::class, 'send']);
Route::post('/messages/mark-as-read/{userId}', [MessageController::class, 'markAsRead']);

// ------------------ CUSTOMERS ------------------ //
Route::get('/customers', function () {
    return \App\Models\User::select('id', 'name')->get();
});

// ------------------ TRANSACTIONS ------------------ //
Route::post('/transactions', [TransactionController::class, 'store']);
Route::get('/transactions', [TransactionController::class, 'index']);
Route::put('/transactions/{id}', [TransactionController::class, 'update']);
Route::delete('/transactions/{id}', [TransactionController::class, 'destroy']);
Route::get('/top-sales', [TransactionController::class, 'topSales']);

// ------------------ SALES TRENDS ------------------ //
Route::get('/sales-trends', function (Request $request) {
    $period = $request->query('period', 'month'); // default: month

    switch ($period) {
        case 'day': $format = '%Y-%m-%d'; break;
        case 'week': $format = '%x-W%v'; break;
        case 'year': $format = '%Y'; break;
        default: $format = '%Y-%m';
    }

    return DB::table('transactions')
        ->select(
            DB::raw("DATE_FORMAT(created_at, '$format') as period"),
            DB::raw('SUM(unit_price * quantity) as total_sales')
        )
        ->groupBy('period')
        ->orderBy('period', 'asc')
        ->get();
});

// ------------------ CUSTOM SHIRTS ------------------ //
Route::get('/custom-shirts', [CustomShirtApiController::class, 'index']);
Route::post('/custom-shirts/{id}/approve', [CustomShirtApiController::class, 'approve']);
Route::post('/custom-shirts/{id}/reject', [CustomShirtApiController::class, 'reject']);

// ------------------ APPOINTMENTS ------------------ //
Route::prefix('appointments')->group(function () {
    Route::post('/', [AppointmentController::class, 'store']);
    Route::get('/', [AppointmentController::class, 'index']);
    Route::put('{id}/approve', [AppointmentController::class, 'approve']);
    Route::put('{id}/reject', [AppointmentController::class, 'reject']);
    Route::get('verify/{token}', [AppointmentController::class, 'verify']);
});
