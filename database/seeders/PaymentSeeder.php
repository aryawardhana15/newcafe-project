<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        
        DB::table('payments')->delete();

        DB::table('payments')->insert([
            [
                'id' => 1,
                'payment_method' => 'Transfer Bank',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 2,
                'payment_method' => 'Cash on Delivery (COD)',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);

        // Enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
}
