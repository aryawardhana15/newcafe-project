<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Status;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Hapus data yang ada terlebih dahulu
        DB::table('statuses')->truncate();

        // Insert data baru
        DB::table('statuses')->insert([
            [
                'id' => 1,
                'order_status' => 'approve',
                'style' => 'success',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 2,
                'order_status' => 'pending',
                'style' => 'warning',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 3,
                'order_status' => 'reject',
                'style' => 'danger',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 4,
                'order_status' => 'done',
                'style' => 'primary',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 5,
                'order_status' => 'cancel',
                'style' => 'secondary',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);
    }
}
