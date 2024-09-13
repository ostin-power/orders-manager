<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;

class WebOrderControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test the index method.
     *
     * @return void
     */
    public function test_index_order_list() {
        // Mock external API responses
        $mockOrders = [
            'orders' => [
                ['id' => 1, 'name' => 'Order 1', 'description' => 'Description 1', 'date' => '2024-09-11'],
                ['id' => 2, 'name' => 'Order 2', 'description' => 'Description 2', 'date' => '2024-09-12'],
            ]
        ];

        $mockProducts = [
            'products' => [
                ['id' => 1, 'name' => 'Product 1'],
                ['id' => 2, 'name' => 'Product 2'],
            ]
        ];

        Http::fake([
            'http://api:9005/api/v1/orders' => Http::response($mockOrders, 200),
            'http://api:9005/api/v1/products' => Http::response($mockProducts, 200),
        ]);

        $response = $this->get(route('orders.index'));

        // Assert the response status and content
        $response->assertStatus(200);
        $response->assertViewIs('orders.index');
        $response->assertViewHas('orders', $mockOrders['orders']);
        $response->assertViewHas('products', $mockProducts['products']);
    }

    /**
     * Test the show method.
     *
     * @return void
     */
    public function test_show_order() {
        $mockOrder = [
            'order' => [
                'id'            => 1,
                'name'          => 'Order 1',
                'description'   => 'Description 1',
                'date'          => '2024-09-11',
                'products' => [
                    [
                        'id'    => 1,
                        'name'  => 'Product 1',
                        'price' => '10$',
                        'pivot' => [
                            'quantity' => 1,
                        ]
                    ],
                ]
            ],
        ];

        // Mock external API response
        Http::fake([
            'http://api:9005/api/v1/orders/1' => Http::response($mockOrder, 200),
        ]);

        // Perform a GET request to the show route
        $response = $this->get(route('orders.show', ['id' => 1]));

        // Assert the response status and content
        $response->assertStatus(200);
        $response->assertViewIs('orders.show');
        $response->assertViewHas('order', $mockOrder['order']);
    }

    /**
     * Test the edit method.
     *
     * @return void
     */
    public function test_edit_order() {
        // Mock external API response
        $mockOrder = [
            'order' => [
                'id'            => 1,
                'name'          => 'Order 1',
                'description'   => 'Description 1',
                'date'          => '2024-09-11'
            ]
        ];

        Http::fake([
            'http://api:9005/api/v1/orders/1' => Http::response($mockOrder, 200),
        ]);

        // Perform a GET request to the edit route
        $response = $this->get(route('orders.edit', ['id' => 1]));

        // Assert the response status and content
        $response->assertStatus(200);
        $response->assertViewIs('orders.edit');
        $response->assertViewHas('order', $mockOrder['order']);
    }

    /**
     * Test the store method.
     *
     * @return void
     */
    public function test_store_order() {
        // Mock external API response
        $mockResponse = [
            'success' => true
        ];

        Http::fake([
            'http://api:9005/api/v1/orders/1' => Http::response($mockResponse, 201),
        ]);

        $response = $this->post(route('orders.store'), [
            'name'          => 'New Order',
            'description'   => 'New Description',
            'date'          => '2024-09-11',
            'products' => [
                ['id' => 1, 'quantity' => 2],
                ['id' => 2, 'quantity' => 4],
            ],
        ]);

        // Assert redirection
        $response->assertStatus(302); //Controller make a redirect to index
        $response->assertRedirect('/');
    }

    /**
     * Test the update method.
     *
     * @return void
     */
    public function test_update_order() {
        // Mock external API response
        $mockResponse = ['success' => true];
        Http::fake([
            'http://api:9005/api/v1/orders/1' => Http::response($mockResponse, 200),
        ]);

        $response = $this->put(route('orders.update', ['id' => 1]), [
            'name'          => 'Updated Order',
            'description'   => 'Updated Description',
            'date'          => '2024-09-12',
        ], ['X-CSRF-TOKEN' => csrf_token()]);

        // Assert redirection
        $response->assertStatus(302); //Controller make a redirect to index
        $response->assertRedirect('/');
    }

    /**
     * Test the delete method.
     *
     * @return void
     */
    public function test_delete_order() {
        // Mock external API response
        Http::fake([
            'http://api:9005/api/v1/orders/1' => Http::response(null, 204),
        ]);

        $response = $this->delete(route('orders.delete', ['id' => 1]), [], ['X-CSRF-TOKEN' => csrf_token()]);

        // Assert redirection
        $response->assertStatus(302); //Controller make a redirect to index
        $response->assertRedirect('/');
    }
}
