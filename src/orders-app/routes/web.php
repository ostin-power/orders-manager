<?php

use App\Http\Controllers\WebOrderController;
use Illuminate\Support\Facades\Route;

/**
 * Get Requests
 */
Route::get('/', [WebOrderController::class, 'index'])->name('orders.index');
Route::get('/show/{id}', [WebOrderController::class, 'show'])->name('orders.show');
Route::get('/order/{id}/edit', [WebOrderController::class, 'edit'])->name('orders.edit');

/**
 * Post Requests
 */
Route::post('/order/store', [WebOrderController::class, 'store'])->name('orders.store');

/**
 * Put Requests
 */
Route::put('/orders/{id}/update', [WebOrderController::class, 'update'])->name('orders.update');

/**
 * Delete Requests
 */
Route::delete('/orders/{id}', [WebOrderController::class, 'delete'])->name('orders.delete');
