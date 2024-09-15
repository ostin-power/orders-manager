<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Foundation\Testing\WithoutMiddleware;
class WebOrderControllerTest extends TestCase {

    use RefreshDatabase;
    use WithoutMiddleware; //Disable CSRF protection or provide the token

    private $_backendUrl;

    /**
     * Set up the test environment.
     *
     * @return void
     */
    protected function setUp(): void {
        parent::setUp();
        $this->_backendUrl = env('BACKEND_URL', 'http://api:9005/api/v1');
    }

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
            $this->_backendUrl.'/orders' => Http::response($mockOrders, 200),
            $this->_backendUrl.'/products' => Http::response($mockProducts, 200),
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
            $this->_backendUrl.'/orders/1' => Http::response($mockOrder, 200),
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
            $this->_backendUrl.'/orders/1' => Http::response($mockOrder, 200),
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
        $mockResponse = ['message' => 'Order created successfully.'];

        Http::fake([
            $this->_backendUrl.'/orders' => Http::response($mockResponse, 201),
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
        $response->assertStatus(201);
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
            $this->_backendUrl.'/orders/1' => Http::response($mockResponse, 200),
        ]);

        $response = $this->put(route('orders.update', ['id' => 1]), [
            'name'          => 'Updated Order',
            'description'   => 'Updated Description',
            'date'          => '2024-09-12',
        ], ['X-CSRF-TOKEN' => csrf_token()]);

        // Assert redirection
        $response->assertStatus(201);
    }

    /**
     * Test the delete method.
     *
     * @return void
     */
    public function test_delete_order() {
        // Mock external API response
        Http::fake([
            $this->_backendUrl.'/orders/1' => Http::response(null, 204),
        ]);

        $response = $this->delete(route('orders.delete', ['id' => 1]), [], ['X-CSRF-TOKEN' => csrf_token()]);

        // Assert redirection
        $response->assertStatus(204);
    }
}
