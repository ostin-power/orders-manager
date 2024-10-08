<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class WebOrderController extends Controller {

    private $backendUrl;

    /**
     * Class Constructor
     */
    public function __construct() {
        $this->backendUrl = env('BACKEND_URL', 'http://api:9005/api/v1');
    }

    /**
     * Returns oders list to frontend-app
     *
     * @param Request $request
     * @return view index
     */
    public function index(Request $request) {
        $response_order = Http::get($this->backendUrl.'/orders', [
            'name'          => $request->input('name'),
            'description'   => $request->input('description'),
            'date'          => $request->input('date'),
        ]);

        $response_products = Http::get($this->backendUrl.'/products');


        if ($response_order->successful()) {
            $orders   = $response_order->json();
            $products = $response_products->successful() ? $response_products->json() : [];
        } else {
            abort(500, 'Error fetching orders from external service.');
        }

        return view('orders.index', [
            'orders'    => $orders['orders'],
            'products'  => $products['products'],
        ]);
    }

    /**
     * Returns specific oders to frontend-app
     *
     * @param int $order_id
     * @return view show
     */
    public function show($id) {
        $response = Http::get($this->backendUrl.'/orders/'.$id);

        if ($response->successful()) {
            $orderDetails = $response->json();
        } else {
            abort(500, 'Error fetching order details from external service.');
        }
        return view('orders.show', ['order' => $orderDetails['order']]);
    }

    /**
     * Return specific data to edit view
     *
     * @param int $id
     * @return view edit
     */
    public function edit($id) {
        $response = Http::get($this->backendUrl.'/orders/'.$id);
        if ($response->successful()) {
            $orderDetails = $response->json();
        } else {
            abort(500, 'Error fetching order details from external service.');
        }
        return view('orders.edit', ['order' => $orderDetails['order']]);
    }

    /**
     * Creates a new order
     *
     * @param Request $request
     * @return view index
     */
    public function store(Request $request) {

        //Creates products associated object
        $product_to_send = [];
        foreach ($request->input('products') as $product) {
            if(!is_null($product['quantity']) && $product['quantity'] > 0) {
                $product_to_send[] = [
                    'product_id' => $product['id'],
                    'quantity'   => $product['quantity']
                ];
            }
        }

        $response = Http::post($this->backendUrl.'/orders', [
            'name'          => $request->input('name'),
            'description'   => $request->input('description'),
            'date'          => $request->input('date'),
            'products'      => $product_to_send
        ]);

        if ($response->successful()) {
            return response()->json(['message' => 'Order created successfully.'], 201);
        } else {
            return response()->json(['message' => 'Error creating order.'], 500);
        }
    }

    /**
     * Updates Order details
     *
     * @param Request $request
     * @param int $id
     * @return json $response
     */
    public function update(Request $request, $id) {
        $response = Http::put($this->backendUrl.'/orders/'.$id, [
            'name'          => $request->input('name'),
            'description'   => $request->input('description'),
            'date'          => $request->input('date'),
        ]);

        if ($response->successful()) {
            return response()->json(['message' => 'Order update successfully.'], 201);
        } else {
            return response()->json(['message' => 'Error updating order.'], 500);
        }
    }

    /**
     * Deletes an order by its ID
     *
     * @param int $id
     * @return json $response
     */
    public function delete($id) {
        $response = Http::delete($this->backendUrl.'/orders/'.$id);
        if ($response->successful()) {
            return response()->json(['message' => 'Order deleted successfully.'], 204);
        } else {
            return response()->json(['message' => 'Error deleting order.'], 500);
        }
    }

}
