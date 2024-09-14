<?php

namespace App\Repositories;

use App\Interfaces\OrderRepositoryInterface;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderRepository implements OrderRepositoryInterface {

    /**
     * Search for orders with asociated products and filters
     *
     * @param Request $request
     * @return object $orders
     */
    public function index(Request $request) {
        $orders = Order::with('products')
        ->when($request->has('name'), function ($query) use ($request) {
            $query->where('name', 'like', '%' . $request->name . '%');
        })
        ->when($request->has('description'), function ($query) use ($request) {
            $query->where('description', 'like', '%' . $request->description . '%');
        })
        ->when($request->has('date'), function ($query) use ($request) {
            $query->whereDate('date', $request->date);
        })
        ->get();

        return $orders;
    }

    /**
     * Store a new Order (and attached products)
     *
     * @param string $name
     * @param string $description
     * @param date $date
     * @param array $products
     * @return object $orders_stored
     */
    public function store(string $name, string $description, $date, array $products = []) {
        $order = Order::create(['name' => $name, 'description' => $description, 'date' => $date]);

        // Attach products with quantities to the order using the pivot table
        $products_to_insert = [];
        foreach ($products as $product) {
            $products_to_insert[$product['product_id']] = ['quantity' => $product['quantity']];
        }

        if(!empty($products_to_insert)) {
            $order->products()->attach($products_to_insert);
        }

        //Return newly created order with its products
        return $order->load('products');
    }

    /**
     * Load the associated products and return the order
     *
     * @param int $order_id
     * @return object $order_details
     */
    public function show ($order_id) {
        $order_details = Order::find($order_id);
        if($order_details) {
            return $order_details->load('products');
        }
        return false;
    }

    /**
     * Update the specified order and its associated products
     *
     * @param int $order_id
     * @param string $name
     * @param string $description
     * @param date $date
     * @param array $products
     * @return object $update_details
     */
    public function update(int $order_id, string $name, string $description, $date, array $products = []) {
        $order = Order::find($order_id);

        if($order) {
            $order->update(['name' => $name, 'description' => $description, 'date' => $date]);
            if(!empty($products)) {
                // Attach products with quantities to the order using the pivot table
                $products_to_insert = [];
                foreach ($products as $product) {
                    $products_to_insert[$product['product_id']] = ['quantity' => $product['quantity']];
                }
                $order->products()->sync($products_to_insert);
            }
            $order_result = $order->load('products');
            return $order_result ? $order_result : false; // Return the updated order with its products
        }
        return [];
    }

    /**
     * Delete the order, automatically removing associated products from the pivot table
     *
     * @param int $order_id
     * @return bool
     */
    public function delete(int $order_id) {
        $order = Order::find($order_id);
        if($order) {
            return $order->delete() ? true : false;
        }
        return [];
    }
}

