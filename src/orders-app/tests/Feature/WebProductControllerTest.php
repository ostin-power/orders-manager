<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\Http;

class WebProductControllerTest extends TestCase
{
    use RefreshDatabase;

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

    public function test_index_returns_products_view_with_products() {
        // Mock external API response
        $mockedProducts = [
            'products' => [
                ['id' => 1, 'name' => 'Product 1', 'price' => 100],
                ['id' => 2, 'name' => 'Product 2', 'price' => 200],
            ],
        ];

        Http::fake([
            $this->_backendUrl.'/products' => Http::response($mockedProducts, 200),
        ]);

        $response = $this->get(route('products.index'));

        // Assertions
        $response->assertStatus(200);
        $response->assertViewIs('products.index');
        $response->assertViewHas('products', [
            ['id' => 1, 'name' => 'Product 1', 'price' => 100],
            ['id' => 2, 'name' => 'Product 2', 'price' => 200],
        ]);
    }

    /**
     * Test the index method when the external API fails.
     */
    public function test_index_handles_external_api_failure() {
        Http::fake([
            $this->_backendUrl.'/products' => Http::response(null, 500),
        ]);

        $response = $this->get(route('products.index'));
        $response->assertStatus(500);
    }

    /**
     * Test the store method.
     *
     * @return void
     */
    public function test_store_product() {
        // Mock external API response
        $mockResponse = [
            'product_created' => [
                ['id' => 1, 'name' => 'New Product', 'price' => 10],
            ]
        ];

        Http::fake([
            $this->_backendUrl.'/products' => Http::response($mockResponse, 201),
        ]);

        $response = $this->post(route('products.store'), [
            'name'  => 'New Product',
            'price' => 10,
        ]);

        // Assert redirection
        $response->assertStatus(201);
    }

    /**
     * Test the update method.
     *
     * @return void
     */
    public function test_update_product_successfully() {
        // Mock the HTTP request to the external backend
        Http::fake([
            $this->_backendUrl.'/products/*' => Http::response(['success' => true], 201)
        ]);

        // Simulate the request data
        $response = $this->putJson(route('products.update', ['id' => 1]),
            ['price' => 100],
            ['X-CSRF-TOKEN' => csrf_token()]
        );

        $response->assertStatus(201);
    }

    /**
     * Test the update failure method.
     *
     * @return void
     */
    public function test_update_product_failure()  {
        // Mock the HTTP request to the external backend with failure
        Http::fake([
            $this->_backendUrl.'/products/products/*' => Http::response(['message' => 'Error updating product.'], 500)
        ]);

        $response = $this->putJson(route('products.update', ['id' => 23456]),
            ['price' => 100],
            ['X-CSRF-TOKEN' => csrf_token()]
        );

        $response->assertStatus(500);
    }

    /**
     * Test the delete method.
     *
     * @return void
     */
    public function test_delete_product() {
        // Mock external API response
        Http::fake([
            $this->_backendUrl.'/products/*' => Http::response(null, 204),
        ]);

        $response = $this->delete(route('products.delete', ['id' => 1]), [], ['X-CSRF-TOKEN' => csrf_token()]);

        // Assert redirection
        $response->assertStatus(204);
    }

    /**
     * Test the delete method.
     *
     * @return void
     */
    public function test_delete_product_failure() {
        // Mock external API response
        Http::fake([
            $this->_backendUrl.'/products/*' => Http::response(['message' => 'Error deleting product.'], 500),
        ]);

        $response = $this->delete(route('products.delete', ['id' => 123456]), [], ['X-CSRF-TOKEN' => csrf_token()]);

        // Assert redirection
        $response->assertStatus(500);
    }
}
