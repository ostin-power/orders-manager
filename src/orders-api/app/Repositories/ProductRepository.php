<?php

namespace App\Repositories;

use App\Interfaces\ProductRepositoryInterface;
use App\Models\Product;

class ProductRepository implements ProductRepositoryInterface {

    /**
     * Returns all product list
     *
     * @return object $products
     */
    public function index() {
        return Product::all();
    }
}
