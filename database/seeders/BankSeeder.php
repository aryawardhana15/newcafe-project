<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Bank;

class BankSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Hapus data yang ada terlebih dahulu
        DB::table('banks')->truncate();

        // Insert data bank
        DB::table('banks')->insert([
            [
                'id' => 1,
                'bank_name' => 'Bank Mandiri',
                'account_number' => '092 7840 1923 7422',
                'logo' => 'bank-mandiri.svg',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 2,
                'bank_name' => 'Bank BRI',
                'account_number' => '058 9092 8274 9125',
                'logo' => 'bank-bri.svg',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 3,
                'bank_name' => 'Bank BCA',
                'account_number' => '088 7182 4291 9123',
                'logo' => 'bank-bca.svg',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 4,
                'bank_name' => 'Bank BNI',
                'account_number' => '098 2937 9823 2341',
                'logo' => 'bank-bni.svg',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
}
