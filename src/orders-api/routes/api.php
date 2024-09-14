<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for orders-api application.
|
*/
Route::group(['prefix' => 'v1'], function() {

    /**
     * Get Routes
     */
    Route::get('/products', [ProductController::class, 'index']);
    Route::get('/products/{id}', [ProductController::class, 'show']);
    Route::get('/orders', [OrderController::class, 'index']);
    Route::get('/orders/{id}', [OrderController::class, 'show']);

    /**
     * Post Routes
     */
    Route::post('/orders', [OrderController::class, 'store']);
    Route::post('/products', [ProductController::class, 'store']);

    /**
     * Put Routes
     */
    Route::put('/orders/{order}', [OrderController::class, 'update']);
    Route::put('/products/{product}', [ProductController::class, 'update']);

    /**
     * Delete Routes
     */
    Route::delete('/orders/{order}', [OrderController::class, 'delete']);
    Route::delete('/products/{product}', [ProductController::class, 'delete']);

});
