<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Website settings with default values
        $websiteSettings = [
            'site_name' => 'PO Kaligrafi Lampu',
            'tagline' => 'Menghadirkan keindahan kaligrafi islami dalam setiap rumah Muslim',
            'email' => 'admin@pokaligrafi.com',
            'phone' => '0812-3456-7890',
            'address' => 'Jl. Pengrajin No. 123, Yogyakarta',
            'site_logo' => '', // Empty by default, will be filled when user uploads
        ];

        foreach ($websiteSettings as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value, 'group' => 'website']
            );
        }

        $this->command->info('Website settings seeded successfully!');
    }
}
