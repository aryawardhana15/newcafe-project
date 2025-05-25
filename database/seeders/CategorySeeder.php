<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::table('categories')->truncate();

        $categories = [
            [
                'id' => 1,
                'category_name' => 'Minuman',
                'description' => 'Berbagai jenis minuman segar',
                'image' => 'drinks.jpg'
            ],
            [
                'id' => 2,
                'category_name' => 'Makanan',
                'description' => 'Menu makanan utama',
                'image' => 'foods.jpg'
            ],
            [
                'id' => 3,
                'category_name' => 'Snack',
                'description' => 'Makanan ringan dan cemilan',
                'image' => 'snacks.jpg'
            ],
            [
                'id' => 4,
                'category_name' => 'Dessert',
                'description' => 'Menu penutup dan makanan manis',
                'image' => 'desserts.jpg'
            ],
            [
                'id' => 5,
                'category_name' => 'Penjualan',
                'description' => 'Pendapatan dari penjualan produk',
                'image' => 'sales.jpg'
            ],
            [
                'id' => 6,
                'category_name' => 'Pembelian Bahan',
                'description' => 'Pengeluaran untuk pembelian bahan baku',
                'image' => 'supplies.jpg'
            ],
            [
                'id' => 7,
                'category_name' => 'Operasional',
                'description' => 'Pengeluaran untuk operasional cafe',
                'image' => 'operational.jpg'
            ],
            [
                'id' => 8,
                'category_name' => 'Gaji Karyawan',
                'description' => 'Pengeluaran untuk gaji karyawan',
                'image' => 'salary.jpg'
            ],
            [
                'id' => 9,
                'category_name' => 'Lain-lain',
                'description' => 'Transaksi lainnya',
                'image' => 'others.jpg'
            ]
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(
                ['id' => $category['id']],
                $category
            );
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
}
