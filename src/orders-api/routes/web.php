<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;


Route::get('/', function (Request $request) {
    $response_order = Http::get(env('BACKEND_URL', 'http://api:9005').'/api/v1/orders', [
        'name'          => $request->input('name'),
        'description'   => $request->input('description'),
        'date'          => $request->input('date'),
    ]);

    $response_products = Http::get(env('BACKEND_URL', 'http://api:9005').'/api/v1/products');


    if ($response_order->successful()) {
        $orders   = $response_order->json();
        $products = $response_products->successful() ? $response_products->json() : [];
    } else {
        abort(500, 'Error fetching orders from external service.');
    }

    return view('orders.index', [
        'orders'    => json_decode(json_encode($orders['orders'])),
        'products'  => json_decode(json_encode($products['products'])),
    ]);
})->name('orders.index');


Route::get('/show/{id}', function (Request $request, $id) {

    $response = Http::get(env('BACKEND_URL', 'http://api:9005').'/api/v1/orders/'.$id);

    if ($response->successful()) {
        $orderDetails = $response->json(); // Decode the JSON response
    } else {
        // Handle the error appropriately
        abort(500, 'Error fetching order details from external service.');
    }
    return view('orders.show', ['order' => json_decode(json_encode($orderDetails['order']))]);

})->name('orders.show');

Route::get('/order/{id}/edit', function (Request $request, $id) {
    $response = Http::get(env('BACKEND_URL', 'http://api:9005').'/api/v1/orders/'.$id);

    if ($response->successful()) {
        $orderDetails = $response->json(); // Decode the JSON response
    } else {
        // Handle the error appropriately
        abort(500, 'Error fetching order details from external service.');
    }
    return view('orders.edit', ['order' => json_decode(json_encode($orderDetails['order']))]);
})->name('orders.edit');

Route::post('/order/store', function (Request $request) {

    dd($request->all());

    $response = Http::post(env('BACKEND_URL', 'http://api:9005').'/api/v1/orders/', [
        'name'          => $request->input('name'),
        'description'   => $request->input('description'),
        'date'          => $request->input('date'),
    ]);

    if ($response->successful()) {
        return redirect()->route('orders.index')->with('success', 'Order created successfully.');
    } else {
        return redirect()->route('orders.index')->with('error', 'Error creating order.');
    }
})->name('orders.store');



Route::put('/orders/{id}/update', function (Request $request, $id) {
    $response = Http::put(env('BACKEND_URL', 'http://api:9005').'/api/v1/orders/'.$id, [
        'name'          => $request->input('name'),
        'description'   => $request->input('description'),
        'date'          => $request->input('date'),
    ]);

    if ($response->successful()) {
        return redirect()->route('orders.index')->with('success', 'Order updated successfully.');
    } else {
        return redirect()->route('orders.index')->with('error', 'Error updating order.');
    }
})->name('orders.update');

Route::delete('/orders/{id}', function ($id) {
    $response = Http::delete(env('BACKEND_URL', 'http://api:9005').'/api/v1/orders/'.$id);

    if ($response->successful()) {
        return redirect()->route('orders.index')->with('success', 'Order deleted successfully.');
    } else {
        return redirect()->route('orders.index')->with('error', 'Error deleting order.');
    }
})->name('orders.delete');
