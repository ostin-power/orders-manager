<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class WebProductController extends Controller {

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
     * @return view index
     */
    public function index() {
        $response_products = Http::get($this->backend_url.'/products');

        if ($response_products->successful()) {
            $products = $response_products->json();
        } else {
            abort(500, 'Error fetching orders from external service.');
        }

        return view('products.index', [
            'products'  => $products['products'],
        ]);
    }

    /**
     * Returns specific products to frontend-app
     *
     * @param int $product_id
     * @return view show
     */
    public function show($id) {
        $response = Http::get($this->backend_url.'/products/'.$id);

        if ($response->successful()) {
            $orderDetails = $response->json();
        } else {
            abort(500, 'Error fetching product details from external service.');
        }
        return view('products.edit', ['product' => $orderDetails['product']]);
    }

    /**
     * Creates a new product
     *
     * @param Request $request
     * @return view index
     */
    public function store(Request $request) {
        $response = Http::post($this->backend_url.'/products/', [
            'name'    => $request->input('name'),
            'price'   => $request->input('price')
        ]);

        if ($response->successful()) {
            return response()->json(['message' => 'Product created successfully.'], 201);
        } else {
            return response()->json(['message' => 'Error creating product.'], 500);
        }
    }

    /**
     * Updates product price
     *
     * @param Request $request
     * @param int $id
     * @return json $response
    */
    public function update(Request $request, $id) {
        $response = Http::put($this->backend_url.'/products/'.$id, [
            'price' => $request->input('price')
        ]);

        if ($response->successful()) {
            return response()->json(['message' => 'Product price update successfully.'], 201);
        } else {
            return response()->json(['message' => 'Error updating product.'], 500);
        }
    }

    /**
     * Deletes product
     *
     * @param int $id
     * @return json $response
     */
    public function delete($id) {
        $response = Http::delete($this->backend_url.'/products/'.$id);
        if ($response->successful()) {
            return response()->json(['message' => 'Product deleted successfully.'], 204);
        } else {
            return response()->json(['message' => 'Error deleting product.'], 500);
        }
    }
}
