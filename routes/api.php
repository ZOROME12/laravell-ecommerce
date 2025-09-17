<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\Api\CustomShirtApiController;
use App\Http\Controllers\Api\AppointmentController;
use App\Http\Controllers\OrderController;


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

Route::get('/messages/{userId}', [MessageController::class, 'fetch']);
Route::post('/messages', [MessageController::class, 'send']);
// in routes/api.php
Route::get('/customers', function () {
    return \App\Models\User::select('id', 'name')->get();
});

// Regular user login & authenticated user route
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->get('/user', [AuthController::class, 'user']);

Route::get('/products', [ProductController::class, 'index']);


Route::post('/messages/mark-as-read/{userId}', [MessageController::class, 'markAsRead']);

Route::post('/transactions', [TransactionController::class, 'store']);
Route::get('/transactions', [TransactionController::class, 'index']);
Route::put('/transactions/{id}', [TransactionController::class, 'update']);
Route::delete('/transactions/{id}', [TransactionController::class, 'destroy']);
Route::get('/top-sales', [TransactionController::class, 'topSales']);

Route::get('/sales-trends', function (Request $request) {
    $period = $request->query('period', 'month'); // default: month

    switch ($period) {
        case 'day':
            $format = '%Y-%m-%d';
            break;
        case 'week':
            $format = '%x-W%v'; // ISO week format
            break;
        case 'year':
            $format = '%Y';
            break;
        default: // month
            $format = '%Y-%m';
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


Route::get('/custom-shirts', [CustomShirtApiController::class, 'index']);
Route::post('/custom-shirts/{id}/approve', [CustomShirtApiController::class, 'approve']);
Route::post('/custom-shirts/{id}/reject', [CustomShirtApiController::class, 'reject']);




Route::prefix('appointments')->group(function () {
    Route::post('/', [AppointmentController::class, 'store']);
    Route::get('/', [AppointmentController::class, 'index']);
    Route::put('{id}/approve', [AppointmentController::class, 'approve']); // remove duplicate "appointments"
    Route::put('{id}/reject', [AppointmentController::class, 'reject']);
    Route::get('verify/{token}', [AppointmentController::class, 'verify']);
});

// Place order 
Route::get('/admin/orders', [OrderController::class, 'apiIndex']);
Route::put('/admin/orders/{id}/status', [OrderController::class, 'updateStatus']);

Route::delete('/admin/orders/{id}', function (Request $request, $id) {
    Auth::shouldUse('admin-api'); // use admin guard
    return app(OrderController::class)->destroy($id);
});
