<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NoteSeeder extends Seeder
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
        
        DB::table('notes')->delete();

        DB::table('notes')->insert([
            [
                'id' => 1,
                'note' => 'Waiting for delivery',
                'style' => 'info',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 2,
                'note' => 'Waiting for payment confirmation',
                'style' => 'warning',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 3,
                'note' => 'Payment proof uploaded',
                'style' => 'primary',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 4,
                'note' => 'Payment confirmed',
                'style' => 'success',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 5,
                'note' => 'Order completed',
                'style' => 'success',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 6,
                'note' => 'Order cancelled',
                'style' => 'danger',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);

        // Enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
}
