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
}
