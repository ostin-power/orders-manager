<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Product;

class ProductControllerTest extends TestCase
{
    /**
     * Test the index method of the ProductController.
     *
     * @return void
     */
    public function test_get_list_of_products() {
        // Use factories to create fake products
        $products = Product::factory()->count(2)->create();
        $response = $this->get('/api/v1/products');

        // Assert the response status and structure
        $response->assertStatus(200);
        $response->assertJson([
            'code' => 200,
            'products' => $products->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => $product->price,
                    'created_at' => $product->created_at->toISOString(),
                    'updated_at' => $product->updated_at->toISOString(),
                ];
            })->toArray()
        ]);
    }

    /**
     * Test successful product creation.
     *
     * @return void
     */
    public function test_successful_product_creation()  {
        $payload = [
            'name'  => 'Test Product',
            'price' => 50,
        ];

        $response = $this->postJson('/api/v1/products', $payload);

        // Assert the response status and structure
        $response->assertStatus(201)
                 ->assertJsonStructure([
                     'code',
                     'product_created' => [
                         'id',
                         'name',
                         'price',
                         'created_at',
                         'updated_at',
                     ]
                 ]);

        // Verify that the product was created in the database
        $this->assertDatabaseHas('products', [
            'name'  => 'Test Product',
            'price' => 50,
        ]);
    }

    /**
     * Test product creation with missing name.
     *
     * @return void
     */
    public function test_product_creation_fails_with_missing_name() {
        $payload = [
            'price' => 50,
        ];

        $response = $this->postJson('/api/v1/products', $payload);

        // Assert the validation error and HTTP status code
        $response->assertStatus(422)->assertJsonValidationErrors(['name']);
    }

    /**
     * Test product creation with invalid price.
     *
     * @return void
     */
    public function test_product_creation_fails_with_invalid_price() {
        $payload = [
            'name'  => 'Test Product',
            'price' => 'invalid_price',
        ];

        $response = $this->postJson('/api/v1/products', $payload);

        // Assert the validation error and HTTP status code
        $response->assertStatus(422)->assertJsonValidationErrors(['price']);
    }
}
