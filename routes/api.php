<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RestaurantController;
use App\Http\Controllers\OrderController;

// Get all restaurants for a specific city (without menus)
Route::get('/restaurants', [RestaurantController::class, 'getRestaurantsByCity']);

// Get menu for a specific restaurant by ID
Route::get('/restaurants/{id}/menu', [RestaurantController::class, 'getMenuByRestaurantId']);

// Create an order
Route::post('/orders', [OrderController::class, 'create']);

// Get order details
Route::get('/orders/{id}', [OrderController::class, 'show']);

// Update order status
Route::patch('/orders/{id}/status', [OrderController::class, 'updateStatus']);

// Cancel an order
Route::delete('/orders/{id}', [OrderController::class, 'destroy']);
