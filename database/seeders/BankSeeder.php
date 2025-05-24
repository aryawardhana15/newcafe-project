<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BankSeeder extends Seeder
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
        
        DB::table('banks')->delete();

        DB::table('banks')->insert([
            [
                'id' => 1,
                'bank_name' => 'Mandiri',
                'account_number' => '092 7840 1923 7422',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 2,
                'bank_name' => 'BRI',
                'account_number' => '058 9092 8274 9125',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 3,
                'bank_name' => 'BCA',
                'account_number' => '088 7182 4291 9123',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 4,
                'bank_name' => 'BNI',
                'account_number' => '098 2937 9823 2341',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);

        // Enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
}
