<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run()
    {
        Product::create([
            'name' => 'Kaligrafi Lampu Allah',
            'description' => 'Kaligrafi lampu dengan tulisan Allah yang indah, memberikan cahaya yang menenangkan dan membawa keberkahan di rumah Anda.',
            'price' => 350000,
            'min_quota' => 10,
            'current_batch' => 12,
            'images' => json_encode([
                'kaligrafi-allah-1.jpg',
                'kaligrafi-allah-2.jpg',
                'kaligrafi-allah-3.jpg'
            ]),
            'specifications' => json_encode([
                'ukuran' => '40x40 cm',
                'bahan' => 'Kayu jati premium',
                'warna_cahaya' => 'Kuning emas hangat',
                'daya' => '5W LED',
                'garansi' => '1 tahun',
                'berat' => '2.5 kg',
                'asal' => 'Yogyakarta'
            ]),
            'is_active' => true
        ]);
        
        Product::create([
            'name' => 'Kaligrafi Lampu Muhammad',
            'description' => 'Kaligrafi lampu dengan tulisan Muhammad, cahaya yang menenangkan untuk ruang keluarga atau tempat ibadah.',
            'price' => 380000,
            'min_quota' => 8,
            'current_batch' => 5,
            'images' => json_encode(['kaligrafi-muhammad-1.jpg']),
            'specifications' => json_encode([
                'ukuran' => '45x45 cm',
                'bahan' => 'Kayu mahoni',
                'warna_cahaya' => 'Putih hangat',
                'daya' => '7W LED'
            ]),
            'is_active' => true
        ]);
    }
}