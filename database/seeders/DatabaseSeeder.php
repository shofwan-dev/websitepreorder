<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Product; // Tambahkan ini
use App\Models\Batch;   // Tambahkan ini
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user
        $admin = User::firstOrCreate(
            ['email' => 'admin@pokaligrafi.com'],
            [
                'name' => 'Admin PO Kaligrafi',
                'password' => Hash::make('password123'),
                'role' => 'admin',
                'phone' => '081234567890',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );
        
        // Create manager user
        $manager = User::firstOrCreate(
            ['email' => 'manager@pokaligrafi.com'],
            [
                'name' => 'Production Manager',
                'password' => Hash::make('password123'),
                'role' => 'manager',
                'phone' => '081234567891',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );
        
        // Create regular test user
        User::firstOrCreate(
            ['email' => 'user@pokaligrafi.com'],
            [
                'name' => 'Test Customer',
                'password' => Hash::make('password123'),
                'role' => 'user',
                'phone' => '081234567892',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );
        
        // Check if Product model exists before using it
        if (class_exists(Product::class)) {
            // Create products with images as array
            $product = Product::firstOrCreate(
                ['name' => 'Kaligrafi Lampu Allah'],
                [
                    'description' => 'Kaligrafi lampu dengan tulisan Allah yang indah, dilengkapi LED premium',
                    'price' => 350000,
                    'min_quota' => 10,
                    'current_quota' => 0,
                    'images' => [], // Empty array, not string
                    'is_active' => true,
                ]
            );
            
            // Create second product
            $product2 = Product::firstOrCreate(
                ['name' => 'Kaligrafi Lampu Muhammad'],
                [
                    'description' => 'Kaligrafi lampu dengan tulisan Muhammad SAW yang elegan',
                    'price' => 375000,
                    'min_quota' => 10,
                    'current_quota' => 0,
                    'images' => [], // Empty array, not string
                    'is_active' => true,
                ]
            );
            
            // Check if Batch model exists before using it
            if (class_exists(Batch::class)) {
                // Create batches
                Batch::firstOrCreate(
                    ['batch_number' => 'BATCH-2024-001'],
                    [
                        'product_id' => $product->id,
                        'target_quantity' => 15,
                        'current_quantity' => 12,
                        'status' => 'production',
                        'production_start_date' => now()->subDays(5),
                        'estimated_completion_date' => now()->addDays(10),
                        'created_by' => $admin->id,
                    ]
                );
                
                Batch::firstOrCreate(
                    ['batch_number' => 'BATCH-2024-002'],
                    [
                        'product_id' => $product2->id,
                        'target_quantity' => 20,
                        'current_quantity' => 7,
                        'status' => 'collecting',
                        'production_start_date' => now()->addDays(5),
                        'estimated_completion_date' => now()->addDays(20),
                        'created_by' => $manager->id,
                    ]
                );
            }
        }
        
        $this->command->info('Seeding completed successfully!');
        $this->command->info('-------------------------------');
        $this->command->info('Admin: admin@pokaligrafi.com / password123');
        $this->command->info('Manager: manager@pokaligrafi.com / password123');
        $this->command->info('User: user@pokaligrafi.com / password123');
    }
}