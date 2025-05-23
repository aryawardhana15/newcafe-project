<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $this->call([
            UserSeeder::class,
            BankSeeder::class,
            RoleSeeder::class,
            ProductSeeder::class,
            NoteSeeder::class,
            PaymentSeeder::class,
            StatusSeeder::class,
            CategorySeeder::class
        ]);

        // Seed Statuses
        DB::table('statuses')->insert([
            ['id' => 1, 'order_status' => 'approve', 'style' => 'success'],
            ['id' => 2, 'order_status' => 'pending', 'style' => 'warning'],
            ['id' => 3, 'order_status' => 'reject', 'style' => 'danger'],
            ['id' => 4, 'order_status' => 'done', 'style' => 'primary'],
            ['id' => 5, 'order_status' => 'cancel', 'style' => 'secondary'],
        ]);

        // Seed Payment Methods
        DB::table('payments')->insert([
            ['id' => 1, 'payment_method' => 'Transfer Bank'],
            ['id' => 2, 'payment_method' => 'Cash on Delivery'],
        ]);

        // Seed Banks
        DB::table('banks')->insert([
            [
                'id' => 1,
                'bank_name' => 'Mandiri',
                'account_number' => '092 7840 1923 7422',
            ],
            [
                'id' => 2,
                'bank_name' => 'BRI',
                'account_number' => '058 9092 8274 9125',
            ],
            [
                'id' => 3,
                'bank_name' => 'BCA',
                'account_number' => '088 7182 4291 9123',
            ],
            [
                'id' => 4,
                'bank_name' => 'BNI',
                'account_number' => '098 2937 9823 2341',
            ],
        ]);
    }
}
