<?php

namespace Database\Seeders;

use App\Models\Payment;
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

        $payments = [
            [
                'id' => Payment::BANK_TRANSFER,
                'name' => 'Transfer Bank',
                'description' => 'Pembayaran melalui transfer bank',
                'is_active' => true
            ],
            [
                'id' => Payment::CASH_ON_DELIVERY,
                'name' => 'Cash on Delivery',
                'description' => 'Pembayaran tunai saat pengiriman',
                'is_active' => true
            ]
        ];

        foreach ($payments as $payment) {
            Payment::create($payment);
        }

        // Enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
}
