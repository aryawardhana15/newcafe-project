<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\{DB, Hash};

class AdminSeeder extends Seeder
{
    public function run()
    {
        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        
        // Create default admin user
        User::create([
            'fullname' => 'Admin',
            'username' => 'admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make('admin123'),
            'role_id' => 1,
            'phone' => '081234567890',
            'gender' => 'M',
            'address' => 'Admin Address',
            'image' => 'default.jpg',
            'coupon' => 0,
            'point' => 0
        ]);

        // Enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
} 