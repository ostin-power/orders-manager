<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class WebOrderController extends Controller {

    private $backend_url;

    /**
     * Class Constructor
     */
    public function __construct() {
        $this->backend_url = env('BACKEND_URL', 'http://api:9005/api/v1');
    }

    /**
     * Returns oders list to frontend-app
     *
     * @param Request $request
     * @return view index
     */
    public function index(Request $request) {
        $response_order = Http::get($this->backend_url.'/orders', [
            'name'          => $request->input('name'),
            'description'   => $request->input('description'),
            'date'          => $request->input('date'),
        ]);

        $response_products = Http::get($this->backend_url.'/products');


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
        $response = Http::get($this->backend_url.'/orders/'.$id);

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
        $response = Http::get($this->backend_url.'/orders/'.$id);
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

        $response = Http::post($this->backend_url.'/orders/', [
            'name'          => $request->input('name'),
            'description'   => $request->input('description'),
            'date'          => $request->input('date'),
            'products'      => $product_to_send
        ]);



        if ($response->successful()) {
            return redirect()->route('orders.index')->with('success', 'Order created successfully.');
        } else {
            return redirect()->route('orders.index')->with('error', 'Error creating order.');
        }
    }

    /**
     * Updates Order details
     *
     * @param Request $request
     * @param int $id
     * @return view index
     */
    public function update(Request $request, $id) {
        $response = Http::put($this->backend_url.'/orders/'.$id, [
            'name'          => $request->input('name'),
            'description'   => $request->input('description'),
            'date'          => $request->input('date'),
        ]);

        if ($response->successful()) {
            return redirect()->route('orders.index')->with('success', 'Order updated successfully.');
        } else {
            return redirect()->route('orders.index')->with('error', 'Error updating order.');
        }
    }

    /**
     * Deletes an order by its ID
     *
     * @param int $id
     * @return view index
     */
    public function delete($id) {
        $response = Http::delete($this->backend_url.'/orders/'.$id);
        if ($response->successful()) {
            return redirect()->route('orders.index')->with('success', 'Order deleted successfully.');
        } else {
            return redirect()->route('orders.index')->with('error', 'Error deleting order.');
        }
    }

}
