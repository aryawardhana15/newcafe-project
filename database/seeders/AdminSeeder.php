<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'fullname' => 'Administrator',
            'username' => 'admin',
            'email' => 'admin@cafe.com',
            'password' => Hash::make('admin123'),
            'role_id' => 1, // Admin role
            'image' => 'default.jpg',
            'phone' => '08123456789',
            'gender' => 'L',
            'address' => 'Alamat Admin',
            'coupon' => 0,
            'point' => 0
        ]);
    }
} 