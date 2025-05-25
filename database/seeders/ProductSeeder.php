<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::table('products')->truncate();

        $products = [
            // Minuman
            [
                'product_name' => 'Cafe Latte',
                'description' => 'Espresso dengan steamed milk dan sedikit foam',
                'price' => 28000,
                'image' => 'cafe-latte.jpg',
                'category_id' => 2, // Minuman
                'user_id' => 1,
                'is_available' => true
            ],
            [
                'product_name' => 'Cappuccino',
                'description' => 'Espresso dengan steamed milk dan foam yang tebal',
                'price' => 28000,
                'image' => 'cappuccino.jpg',
                'category_id' => 2,
                'user_id' => 1,
                'is_available' => true
            ],
            [
                'product_name' => 'Americano',
                'description' => 'Espresso dengan air panas',
                'price' => 25000,
                'image' => 'americano.jpg',
                'category_id' => 2,
                'user_id' => 1,
                'is_available' => true
            ],
            // Makanan
            [
                'product_name' => 'Nasi Goreng Spesial',
                'description' => 'Nasi goreng dengan telur, ayam, dan sayuran',
                'price' => 35000,
                'image' => 'nasi-goreng.jpg',
                'category_id' => 1, // Makanan
                'user_id' => 1,
                'is_available' => true
            ],
            [
                'product_name' => 'Club Sandwich',
                'description' => 'Sandwich dengan ayam, telur, keju, dan sayuran',
                'price' => 32000,
                'image' => 'club-sandwich.jpg',
                'category_id' => 1,
                'user_id' => 1,
                'is_available' => true
            ],
            // Snack
            [
                'product_name' => 'French Fries',
                'description' => 'Kentang goreng crispy dengan saus',
                'price' => 20000,
                'image' => 'french-fries.jpg',
                'category_id' => 3, // Snack
                'user_id' => 1,
                'is_available' => true
            ],
            [
                'product_name' => 'Chicken Wings',
                'description' => 'Sayap ayam goreng dengan saus BBQ',
                'price' => 30000,
                'image' => 'chicken-wings.jpg',
                'category_id' => 3,
                'user_id' => 1,
                'is_available' => true
            ],
            // Dessert
            [
                'product_name' => 'Chocolate Cake',
                'description' => 'Kue coklat lembut dengan saus coklat',
                'price' => 25000,
                'image' => 'chocolate-cake.jpg',
                'category_id' => 4, // Dessert
                'user_id' => 1,
                'is_available' => true
            ],
            [
                'product_name' => 'Ice Cream Sundae',
                'description' => 'Es krim vanilla dengan saus coklat dan kacang',
                'price' => 22000,
                'image' => 'ice-cream-sundae.jpg',
                'category_id' => 4,
                'user_id' => 1,
                'is_available' => true
            ]
        ];

        foreach ($products as $product) {
            Product::create($product);
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
} 