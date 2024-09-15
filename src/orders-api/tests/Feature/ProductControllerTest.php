<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

class ProductControllerTest extends TestCase {

    use RefreshDatabase, WithFaker;

    protected $product;

    /**
     * Set up shared data for the tests.
     *
     * @return void
     */
    protected function setUp(): void {
        parent::setUp();

        // Create a common product for use in tests
        $this->product = Product::factory()->create();
    }

    /**
     * Test the index method of the ProductController.
     *
     * @return void
     */
    public function test_get_list_of_products() {
        // Create additional products
        Product::factory()->count(2)->create();

        $response = $this->getJson('/api/v1/products');

        // Assert the response status and structure
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'code',
            'products' => [
                '*' => [
                    'id',
                    'name',
                    'price',
                    'created_at',
                    'updated_at',
                ]
            ]
        ]);
    }

    /**
     * Test successful product creation.
     *
     * @return void
     */
    public function test_successful_product_creation() {
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

    /**
     * Test product show info.
     *
     * @return void
     */
    public function test_show_product() {
        $response = $this->getJson("/api/v1/products/{$this->product->id}");

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'code',
                    'product' => [
                        'id',
                        'name',
                        'price',
                        'created_at',
                        'updated_at'
                    ]
                ])
                ->assertJson([
                    'code' => 200,
                    'product' => [
                        'id' => $this->product->id,
                        'name' => $this->product->name,
                        'price' => $this->product->price
                    ]
                ]);
    }

    /**
     * Test product not found.
     *
     * @return void
     */
    public function test_show_product_not_found() {
        // Send a GET request to a non-existent product
        $response = $this->getJson('/api/v1/products/9999');

        $response->assertStatus(404)
                ->assertJson([
                    'code' => 404,
                    'message' => 'Product not found'
                ]);
    }

    /**
     * Test updating a product.
     *
     * @return void
     */
    public function test_update_product() {
        $updatedData = [
            'price' => 150
        ];

        $response = $this->putJson("/api/v1/products/{$this->product->id}", $updatedData);

        $response->assertStatus(204);

        // Verify the product's price was updated in the database
        $this->assertDatabaseHas('products', [
            'id' => $this->product->id,
            'price' => 150
        ]);
    }

    /**
     * Test product update failed - validation.
     *
     * @return void
     */
    public function test_update_product_validation_error() {
        $response = $this->putJson("/api/v1/products/{$this->product->id}", []);

        // Assert the response status is 422 Unprocessable Entity and has validation error
        $response->assertStatus(422)->assertJsonValidationErrors('price');
    }

    /**
     * Test updating a non-existent product.
     *
     * @return void
     */
    public function test_update_product_not_found() {
        $response = $this->putJson('/api/v1/products/9999', [
            'price' => 150
        ]);

        $response->assertStatus(404)
                ->assertJson([
                    'code' => 404,
                    'message' => 'Product not found'
                ]);
    }

    /**
     * Test deleting a product.
     *
     * @return void
     */
    public function test_delete_product() {
        $response = $this->deleteJson("/api/v1/products/{$this->product->id}");

        $response->assertStatus(204);

        // Verify the product was deleted from the database
        $this->assertDatabaseMissing('products', [
            'id' => $this->product->id
        ]);
    }

    /**
     * Test deleting a non-existent product.
     *
     * @return void
     */
    public function test_delete_product_not_found() {
        $response = $this->deleteJson('/api/v1/products/9999');

        // Assert the response status is 404 and contains the correct error message
        $response->assertStatus(404)
                ->assertJson([
                    'code' => 404,
                    'message' => 'Product not found'
                ]);
    }
}
