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

    protected $order;
    protected $product;

    /**
     * Set up shared data for the tests.
     *
     * @return void
     */
    protected function setUp(): void {
        parent::setUp();

        // Create a common order and product for use in tests
        $this->order = Order::factory()->create();
        $this->product = Product::factory()->create();

        // Attach the product to the order with a quantity
        $this->order->products()->attach($this->product->id, ['quantity' => 3]);
    }

    /**
     * Test fetching a list of orders.
     *
     * @return void
     */
    public function test_get_list_of_orders() {
        // Fetch the list of orders
        $response = $this->getJson('/api/v1/orders');

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
        $data = [
            'name' => 'New Order',
            'description' => 'Order description',
            'date' => now()->toDateString(),
            'products' => [
                [
                    'product_id' => $this->product->id,
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
     * Test invalid order creation.
     *
     * @return void
     */
    public function test_order_creation_with_missing_param() {
        $data = [
            'date' => now()->toDateString(),
            'products' => [
                [
                    'product_id' => $this->product->id,
                    'quantity' => 5,
                ]
            ]
        ];
        $response = $this->postJson('/api/v1/orders', $data);
        $response->assertStatus(422); // Expecting validation error due to missing 'name'
    }

    /**
     * Test fetching a specific order by ID.
     *
     * @return void
     */
    public function test_show_order_by_id() {
        $response = $this->getJson('/api/v1/orders/' . $this->order->id);

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
     * Test order not found.
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
        $updatedData = [
            'name' => 'Updated Order Name',
            'description' => 'Updated description',
            'date' => now()->toDateString(),
            'products' => [
                [
                    'product_id' => $this->product->id,
                    'quantity' => 10,
                ]
            ]
        ];

        $response = $this->putJson('/api/v1/orders/' . $this->order->id, $updatedData);

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
     * Test updating a non-existent order.
     *
     * @return void
     */
    public function test_update_order_not_found() {
        $response = $this->putJson('/api/v1/orders/9999', [
            'name' => 'Updated Order Name',
            'description' => 'Updated description',
            'date' => now()->toDateString(),
            'products' => [
                [
                    'product_id' => $this->product->id,
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
     * Test deleting an order.
     *
     * @return void
     */
    public function test_delete_order() {
        $response = $this->deleteJson("/api/v1/orders/{$this->order->id}");

        $response->assertStatus(204);

        // Verify the order was deleted from the database
        $this->assertDatabaseMissing('orders', [
            'id' => $this->order->id
        ]);
    }

    /**
     * Test deleting a non-existent order.
     *
     * @return void
     */
    public function test_delete_order_not_found() {
        $response = $this->deleteJson('/api/v1/orders/9999');

        $response->assertStatus(404)
                 ->assertJson([
                     'code' => 404,
                     'message' => 'Order not found'
                 ]);
    }
}
