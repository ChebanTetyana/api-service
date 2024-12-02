<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\RentalController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/rent', [RentalController::class, 'rent']);
    Route::post('/purchase', [PurchaseController::class, 'purchase']);

    Route::patch('/orders/{order_id}/extend', [OrderController::class, 'extendRental']);
    Route::get('/orders/{order_id}/status', [OrderController::class, 'status']);
    Route::get('/users/{user_id}/orders', [OrderController::class, 'history']);

    Route::get('/products/{product_id}', [ProductController::class, 'show']);
    Route::get('/products', [ProductController::class, 'index']);
});

