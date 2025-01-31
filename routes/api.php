<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RestaurantController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AuthController;

// Restaurant Routes
Route::get('/restaurants', [RestaurantController::class, 'getRestaurantsByCity']);
Route::get('/restaurants/{id}/menu', [RestaurantController::class, 'getMenuByRestaurantId']);
Route::get('/restaurants/cities', [RestaurantController::class, 'getAllCities']);
Route::get('/{city}', [RestaurantController::class, 'getRestaurantsByCity']);



// Order Routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/orders', [OrderController::class, 'create']); // Create an order (requires login)
    Route::get('/orders/{id}', [OrderController::class, 'show']); // Get order details
    Route::patch('/orders/{id}/status', [OrderController::class, 'updateStatus']); // Update order status
    Route::delete('/orders/{id}', [OrderController::class, 'destroy']); // Cancel an order
});

// Authentication Routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('/reset-password', [AuthController::class, 'resetPassword']);

Route::fallback(function () {
    return response()->json(['message' => 'Route not found'], 404);
});

