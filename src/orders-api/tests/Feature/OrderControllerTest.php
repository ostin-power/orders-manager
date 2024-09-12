<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Foundation\Testing\WithFaker;

class OrderControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * Test fetching a list of orders.
     *
     * @return void
     */
    public function test_get_list_of_orders() {
        // Create some orders for testing
        $order      = Order::factory()->count(3)->create();
        $response   = $this->getJson('/api/v1/orders');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                    'code',
                    'orders' => [
                        '*' => [
                            'id',
                            'name',
                            'description',
                            'date',
                            'products' => [
                                '*' => [
                                    'id',
                                    'name',
                                    'price',
                                    'pivot' => [
                                        'quantity'
                                    ]
                                ]
                            ]
                        ]
                    ]
                 ]);
    }

    /**
     * Test creating an order.
     *
     * @return void
     */
    public function test_create_order() {
        // Create a product for the order
        $product = Product::factory()->create();

        $data = [
            'name' => 'New Order',
            'description' => 'Order description',
            'date' => now()->toDateString(),
            'products' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 5,
                ]
            ]
        ];

        $response = $this->postJson('/api/v1/orders', $data);

        $response->assertStatus(201)
                 ->assertJsonStructure([
                     'code',
                     'order_created' => [
                         'id',
                         'name',
                         'description',
                         'date',
                         'products' => [
                             '*' => [
                                 'id',
                                 'name',
                                 'price',
                                 'pivot' => [
                                     'quantity'
                                 ]
                             ]
                         ]
                     ]
                 ]);
    }

    /**
     * Test fetching a specific order by ID.
     *
     * @return void
     */
    public function test_show_order_by_id() {
        // Create an order with associated products
        $order      = Order::factory()->create();
        $product    = Product::factory()->create();

        $order->products()->attach($product->id, ['quantity' => 3]);
        $response = $this->getJson('/api/v1/orders/' . $order->id);

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'code',
                     'order' => [
                         'id',
                         'name',
                         'description',
                         'date',
                         'products' => [
                             '*' => [
                                 'id',
                                 'name',
                                 'price',
                                 'pivot' => [
                                     'quantity'
                                 ]
                             ]
                         ]
                     ]
                 ]);
    }

    /**
     * Test updating an order.
     *
     * @return void
     */
    public function test_update_order() {
        // Create an order with products
        $order      = Order::factory()->create();
        $product    = Product::factory()->create();
        $order->products()->attach($product->id, ['quantity' => 3]);

        $updatedData = [
            'name' => 'Test Updated Order Name',
            'description' => 'Updated description for feature testing',
            'date' => now()->toDateString(),
            'products' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 10,
                ]
            ]
        ];

        $response = $this->putJson('/api/v1/orders/' . $order->id, $updatedData);

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'code',
                     'order_update' => [
                         'id',
                         'name',
                         'description',
                         'date',
                         'products' => [
                             '*' => [
                                 'id',
                                 'name',
                                 'price',
                                 'pivot' => [
                                     'quantity'
                                 ]
                             ]
                         ]
                     ]
                 ]);
    }
}
