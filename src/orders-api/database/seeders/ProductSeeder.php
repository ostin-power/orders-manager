<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void {
        $faker = Faker::create();

        DB::table('products')->delete();

        // Create products
        for ($i = 0; $i < 30; $i++) {
            Product::create([
                'name' => $faker->word,
                'price' => $faker->randomFloat(2, 1, 100)
            ]);
        }
    }
}
