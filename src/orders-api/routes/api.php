<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;

// Route::get('/', function () {
//     return "api";
// });

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
    Route::get('/orders', [OrderController::class, 'index']);
    Route::get('/orders/{order}', [OrderController::class, 'show']);

    /**
     * Post Routes
     */
    Route::post('/orders', [OrderController::class, 'store']);

    /**
     * Put Routes
     */
    Route::put('/orders/{order}', [OrderController::class, 'update']);

    /**
     * Delete Routes
     */
    Route::delete('/orders/{order}', [OrderController::class, 'delete']);

});
