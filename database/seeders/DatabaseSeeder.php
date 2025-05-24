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

        // Seed tables without dependencies first
        $this->call([
            RoleSeeder::class,
            CategorySeeder::class,
            StatusSeeder::class,
            PaymentSeeder::class,
            BankSeeder::class,
            NoteSeeder::class,
        ]);

        // Seed user data
        $this->call([
            UserSeeder::class,
        ]);

        // Seed product data
        $this->call([
            ProductSeeder::class,
        ]);

        // Remove duplicate seeding
        DB::table('statuses')->delete();
        DB::table('payments')->delete();
        DB::table('banks')->delete();
    }
}
