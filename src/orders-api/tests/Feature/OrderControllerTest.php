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
     * Test invalid order creation
     *
     * @return void
     */
    public function test_order_creation_with_missing_param() {
        // Create a product for the order
        $product = Order::factory()->create();

        $data = [
            'date' => now()->toDateString(),
            'products' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 5,
                ]
            ]
        ];
        $response = $this->postJson('/api/v1/orders', $data);
        $response->assertStatus(422);
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
     * Test order not found
     *
     * @return void
     */
    public function test_show_order_not_found() {
        // Send a GET request to a non-existent order
        $response = $this->getJson('/api/v1/orders/9999');

        $response->assertStatus(404)
                ->assertJson([
                    'code' => 404,
                    'message' => 'Order not found'
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

    /**
     * Test update order not found
     *
     * @return void
     */
    public function test_update_order_not_found() {
        $order      = Order::factory()->create();
        $product    = Product::factory()->create();
        $order->products()->attach($product->id, ['quantity' => 3]);

        $response = $this->putJson('/api/v1/orders/9999', [
            'name' => 'Test Updated Order Name',
            'description' => 'Updated description for feature testing',
            'date' => now()->toDateString(),
            'products' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 10,
                ]
            ]
        ]);

        $response->assertStatus(404)
                ->assertJson([
                    'code' => 404,
                    'message' => 'Order not found'
                ]);
    }

    /**
     * Test delete order
     *
     * @return void
     */
    public function test_delete_product() {
        $product  = Order::factory()->create();
        $response = $this->deleteJson("/api/v1/orders/{$product->id}");

        $response->assertStatus(204);

        // Verify the product was deleted from the database
        $this->assertDatabaseMissing('orders', [
            'id' => $product->id
        ]);
    }

    /**
     * Test delete product not found
     *
     * @return void
     */
    public function test_delete_order_not_found() {
        $response = $this->deleteJson('/api/v1/orders/9999');

        // Assert the response status is 404 and contains the correct error message
        $response->assertStatus(404)
                ->assertJson([
                    'code' => 404,
                    'message' => 'Order not found'
                ]);
    }
}
