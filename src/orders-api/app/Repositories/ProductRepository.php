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

    /**
     * Store a new product
     *
     * @param string $name
     * @param int $price
     * @return object $product_stored
     */
    public function store(string $name, int $price) {
        return Product::create(['name' => $name, 'price' => $price]);
    }
}
