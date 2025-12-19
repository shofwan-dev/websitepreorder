<?php

namespace Database\Seeders;

use App\Models\Order;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    public function run()
    {
        $orders = [
            [
                'product_id' => 1,
                'customer_name' => 'Ahmad Rizki',
                'customer_city' => 'Bandung',
                'customer_phone' => '081234567890',
                'customer_email' => 'ahmad@gmail.com',
                'amount' => 350000,
                'payment_status' => 'paid',
                'is_verified' => true,
                'is_displayed' => true,
                'verified_at' => now()->subDays(3),
            ],
            [
                'product_id' => 1,
                'customer_name' => 'Siti Nurhaliza',
                'customer_city' => 'Yogyakarta',
                'customer_phone' => '081234567891',
                'customer_email' => 'siti@gmail.com',
                'amount' => 350000,
                'payment_status' => 'paid',
                'is_verified' => true,
                'is_displayed' => true,
                'verified_at' => now()->subDays(2),
            ],
            [
                'product_id' => 1,
                'customer_name' => 'Budi Santoso',
                'customer_city' => 'Surabaya',
                'customer_phone' => '081234567892',
                'customer_email' => 'budi@gmail.com',
                'amount' => 350000,
                'payment_status' => 'paid',
                'is_verified' => true,
                'is_displayed' => true,
                'verified_at' => now()->subDays(1),
            ],
            [
                'product_id' => 1,
                'customer_name' => 'Rina Wijaya',
                'customer_city' => 'Jakarta',
                'customer_phone' => '081234567893',
                'customer_email' => 'rina@gmail.com',
                'amount' => 350000,
                'payment_status' => 'paid',
                'is_verified' => true,
                'is_displayed' => true,
                'verified_at' => now()->subHours(12),
            ],
            [
                'product_id' => 1,
                'customer_name' => 'Dedi Kurniawan',
                'customer_city' => 'Semarang',
                'customer_phone' => '081234567894',
                'customer_email' => 'dedi@gmail.com',
                'amount' => 350000,
                'payment_status' => 'paid',
                'is_verified' => true,
                'is_displayed' => true,
                'verified_at' => now()->subHours(6),
            ],
            [
                'product_id' => 1,
                'customer_name' => 'Maya Sari',
                'customer_city' => 'Malang',
                'customer_phone' => '081234567895',
                'customer_email' => 'maya@gmail.com',
                'amount' => 350000,
                'payment_status' => 'paid',
                'is_verified' => true,
                'is_displayed' => true,
                'verified_at' => now()->subHours(3),
            ],
            [
                'product_id' => 1,
                'customer_name' => 'Fajar Pratama',
                'customer_city' => 'Bali',
                'customer_phone' => '081234567896',
                'customer_email' => 'fajar@gmail.com',
                'amount' => 350000,
                'payment_status' => 'paid',
                'is_verified' => true,
                'is_displayed' => true,
                'verified_at' => now()->subHours(1),
            ],
        ];

        foreach ($orders as $orderData) {
            Order::create($orderData);
        }
    }
}