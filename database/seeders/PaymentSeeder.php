<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Payment;

class PaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('payments')->insert([
            [
                'id' => Payment::BANK_TRANSFER,
                'payment_method' => 'Transfer Bank',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => Payment::CASH_ON_DELIVERY,
                'payment_method' => 'Cash on Delivery',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
}
