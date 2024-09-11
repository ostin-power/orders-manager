<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Order;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;
use App\Models\Product;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void {
        $faker = Faker::create();

        // Truncate the orders table to start fresh
        DB::table('orders')->delete();
        DB::table('order_product')->delete();

        // Create 10 orders
        for ($i = 0; $i < 10; $i++) {
            $order = Order::create([
                'name' => $faker->word,
                'description' => $faker->sentence,
                'date' => $faker->date,
            ]);

            // Attach 1-5 random products to the order with a random quantity
            $products = Product::inRandomOrder()->take(rand(1, 5))->get();
            foreach ($products as $product) {
                $order->products()->attach($product->id, ['quantity' => rand(1, 10)]);
            }
        }
    }
}
