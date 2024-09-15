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
     * Load product
     *
     * @param int $id
     * @return object $product
     */
    public function show(int $id) {
        return Product::find($id);
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

    /**
     * Updates Product price
     *
     * @param int $product_id
     * @param int $price
     * @return bool|array
     */
    public function update(int $id, int $price) {
        $product = Product::find($id);
        if($product) {
            return $product->update(['price' => $price]);
        }
        return [];
    }

    /**
     * Deletes Product
     *
     * @param int $product_id
     * @return bool|array
     */
    public function delete(int $id) {
        $product = Product::find($id);
        if($product) {
            return $product->delete() ? true : false;
        }
        return [];
    }

}
