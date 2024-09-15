<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Order;
use App\Models\Product;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Order::class;

    public function definition() {
        return [
            'name'          => $this->faker->name(),
            'description'   => $this->faker->sentence(),
            'date'          => $this->faker->date(),
        ];
    }

    public function configure() {
        return $this->afterCreating(function (Order $order) {
            $products = Product::factory()->count(3)->create();
            foreach ($products as $product) {
                $order->products()->attach($product->id, ['quantity' => rand(1, 5)]);
            }
        });
    }
}
