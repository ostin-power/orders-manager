<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\Http;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class WebProductControllerTest extends TestCase {

    use RefreshDatabase;
    use WithoutMiddleware; // Disable CSRF protection or provide the token

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
     * Test the index method
     */
    public function test_index_returns_products_view_with_products() {
        // Mock external API response
        $mockedProducts = [
            'products' => [
                ['id' => 1, 'name' => 'Product 1', 'price' => 100],
                ['id' => 2, 'name' => 'Product 2', 'price' => 200],
            ],
        ];

        // Use wildcard (*) to match any URL that starts with /products
        Http::fake([
            $this->_backendUrl.'/products*' => Http::response($mockedProducts, 200),
        ]);

        $response = $this->get(route('products.index'));

        // Assertions
        $response->assertStatus(200);
        $response->assertViewIs('products.index');
        $response->assertViewHas('products', $mockedProducts['products']);
    }

    /**
     * Test index failure
     */
    public function test_index_handles_external_api_failure() {
        Http::fake([
            $this->_backendUrl.'/products*' => Http::response(null, 500),
        ]);

        $response = $this->get(route('products.index'));
        $response->assertStatus(500);
    }

    /**
     * Test the store method
     */
    public function test_store_product() {
        // Mock external API response
        $mockResponse = [
            'product_created' => [
                ['id' => 1, 'name' => 'New Product', 'price' => 10],
            ]
        ];

        Http::fake([
            $this->_backendUrl.'/products*' => Http::response($mockResponse, 201),
        ]);

        $response = $this->post(route('products.store'), [
            'name'  => 'New Product',
            'price' => 10,
        ]);

        // Assert correct status
        $response->assertStatus(201);
    }

    /**
     * Test updating a product successfully
     */
    public function test_update_product_successfully() {
        Http::fake([
            $this->_backendUrl.'/products/*' => Http::response(['success' => true], 201),
        ]);

        // Simulate the request data
        $response = $this->putJson(route('products.update', ['id' => 1]), [
            'price' => 100,
        ], [
            'X-CSRF-TOKEN' => csrf_token(),
        ]);

        $response->assertStatus(201);
    }

    /**
     * Test handling failure during product update
     */
    public function test_update_product_failure() {
        Http::fake([
            $this->_backendUrl.'/products/*' => Http::response(['message' => 'Error updating product.'], 500),
        ]);

        $response = $this->putJson(route('products.update', ['id' => 23456]), [
            'price' => 100,
        ], [
            'X-CSRF-TOKEN' => csrf_token(),
        ]);

        $response->assertStatus(500);
    }

    /**
     * Test deleting a product successfully
     */
    public function test_delete_product() {
        Http::fake([
            $this->_backendUrl.'/products/*' => Http::response(null, 204),
        ]);

        $response = $this->delete(route('products.delete', ['id' => 1]), [], [
            'X-CSRF-TOKEN' => csrf_token(),
        ]);

        // Assert correct status
        $response->assertStatus(204);
    }

    /**
     * Test handling failure during product deletion
     */
    public function test_delete_product_failure() {
        Http::fake([
            $this->_backendUrl.'/products/*' => Http::response(['message' => 'Error deleting product.'], 500),
        ]);

        $response = $this->delete(route('products.delete', ['id' => 123456]), [], [
            'X-CSRF-TOKEN' => csrf_token(),
        ]);

        $response->assertStatus(500);
    }
}
