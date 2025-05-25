<?php

namespace Database\Seeders;

use App\Models\Bank;
use Illuminate\Database\Seeder;

class BankSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $banks = [
            [
                'name' => 'BCA',
                'account_number' => '1234567890',
                'account_name' => 'CAFE PROJECT',
                'is_active' => true
            ],
            [
                'name' => 'BNI',
                'account_number' => '0987654321',
                'account_name' => 'CAFE PROJECT',
                'is_active' => true
            ],
            [
                'name' => 'Mandiri',
                'account_number' => '2468135790',
                'account_name' => 'CAFE PROJECT',
                'is_active' => true
            ]
        ];

        foreach ($banks as $bank) {
            Bank::create($bank);
        }
    }
}
