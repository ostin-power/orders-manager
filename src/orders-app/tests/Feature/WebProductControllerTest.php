<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Http;

class WebProductControllerTest extends TestCase
{
    public function test_index_returns_products_view_with_products() {
        // Mock external API response
        $mockedProducts = [
            'products' => [
                ['id' => 1, 'name' => 'Product 1', 'price' => 100],
                ['id' => 2, 'name' => 'Product 2', 'price' => 200],
            ],
        ];

        Http::fake([
            'http://api:9005/api/v1/products' => Http::response($mockedProducts, 200),
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
            'http://api:9005/api/v1/products' => Http::response(null, 500),
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
            'http://api:9005/api/v1/products' => Http::response($mockResponse, 201),
        ]);

        $response = $this->post(route('products.store'), [
            'name'  => 'New Product',
            'price' => 10,
        ]);

        // Assert redirection
        $response->assertStatus(201);
    }
}
