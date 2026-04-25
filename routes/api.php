<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/login', [AuthController::class, 'login']);

// Public Routes
Route::get('/rooms', [\App\Http\Controllers\Api\RoomController::class, 'index']);
Route::get('/rooms/{id}', [\App\Http\Controllers\Api\RoomController::class, 'show']);

// Protected Routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    
    Route::get('/user', function (Request $request) {
        return response()->json([
            'status' => 'success',
            'data' => $request->user()->load('room')
        ]);
    });

    // Bookings
    Route::get('/bookings', [\App\Http\Controllers\Api\BookingController::class, 'index']);
    Route::get('/bookings/{id}', [\App\Http\Controllers\Api\BookingController::class, 'show']);
    Route::post('/bookings', [\App\Http\Controllers\Api\BookingController::class, 'store']);

    // Transactions
    Route::get('/transactions', [\App\Http\Controllers\Api\TransactionController::class, 'index']);
    Route::get('/transactions/{id}', [\App\Http\Controllers\Api\TransactionController::class, 'show']);
    Route::get('/transactions/{id}/receipt/preview', [\App\Http\Controllers\Api\TransactionController::class, 'previewReceipt']);
    Route::post('/transactions/{id}/receipt/send', [\App\Http\Controllers\Api\TransactionController::class, 'sendReceiptEmail']);
    Route::delete('/messages/{id}', [\App\Http\Controllers\Api\TransactionController::class, 'deleteReceipt']);

    // Messages
    Route::get('/messages', [\App\Http\Controllers\Api\MessageController::class, 'index']);
    Route::post('/messages', [\App\Http\Controllers\Api\MessageController::class, 'store']);
    Route::get('/messages/history/{userId}', [\App\Http\Controllers\Api\MessageController::class, 'getChatHistory']);

    // Tenants (Admin only)
    Route::apiResource('tenants', \App\Http\Controllers\Api\TenantController::class);
    Route::post('/tenants/{id}/renew', [\App\Http\Controllers\Api\TenantController::class, 'renewContract']);

    // Room CRUD (Admin only logic in controller)
    Route::post('/rooms', [\App\Http\Controllers\Api\RoomController::class, 'store']);
    Route::put('/rooms/{id}', [\App\Http\Controllers\Api\RoomController::class, 'update']);
    Route::delete('/rooms/{id}', [\App\Http\Controllers\Api\RoomController::class, 'destroy']);
});
