<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AppointmentController;
use App\Http\Controllers\Api\CustomShirtApiController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SquareController;
use App\Http\Controllers\TransactionController;
use App\Models\User;

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

    // Tracking
    Route::get('/tracking/orders', [OrderController::class, 'apiIndex']); // Note: This is a duplicate of /admin/orders
    Route::put('/tracking/orders/{id}/tracking-stage', [OrderController::class, 'updateTrackingStage']);
    Route::put('/admin/orders/{id}/tracking-stage', [OrderController::class, 'updateTrackingStage']); // Note: Duplicate functionality

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::put('/notifications/{notification}/read', [NotificationController::class, 'markAsRead']);

    // Square POS
    Route::get('/square/products', [SquareController::class, 'getProducts']);
    Route::post('/square/record-sale', [SquareController::class, 'recordSale']);
    Route::get('/square/sales', [SquareController::class, 'getSalesHistory']);  
    Route::get('/square/inventory', [SquareController::class, 'getInventory']); 
});

// ------------------ MESSAGES ------------------ //
Route::get('/messages/{userId}', [MessageController::class, 'fetch']);
Route::post('/messages', [MessageController::class, 'send']);
Route::post('/messages/mark-as-read/{userId}', [MessageController::class, 'markAsRead']);

// ------------------ CUSTOMERS ------------------ //
Route::get('/customers', function () {
    return User::select('id', 'name')->get();
});

// ------------------ TRANSACTIONS ------------------ //
Route::post('/transactions', [TransactionController::class, 'store']);
Route::get('/transactions', [TransactionController::class, 'index']);
Route::put('/transactions/{id}', [TransactionController::class, 'update']);
Route::delete('/transactions/{id}', [TransactionController::class, 'destroy']);
Route::get('/top-sales', [TransactionController::class, 'topSales']);

// ------------------ SALES TRENDS ------------------ //
Route::get('/sales-trends', function (Request $request) {
    $period = $request->query('period', 'month');
    $format = match ($period) {
        'day' => '%Y-%m-%d',
        'week' => '%x-W%v',
        'year' => '%Y',
        default => '%Y-%m',
    };
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