<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

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
}
