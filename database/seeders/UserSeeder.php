<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'name' => 'Admin PO Kaligrafi',
            'email' => 'admin@pokaligrafi.com',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
        ]);
        
        User::create([
            'name' => 'Ahmad Rizki',
            'email' => 'ahmad@gmail.com',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
        ]);
        
        User::create([
            'name' => 'Siti Nurhaliza',
            'email' => 'siti@gmail.com',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
        ]);
    }
}