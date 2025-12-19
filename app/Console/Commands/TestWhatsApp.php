<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\WhatsAppService;
use App\Models\Order;

class TestWhatsApp extends Command
{
    protected $signature = 'whatsapp:test 
                            {phone? : Nomor WhatsApp (contoh: 081234567890)}
                            {message? : Pesan yang akan dikirim}
                            {--stage= : Stage produksi untuk testing (po_open, production, shipping, dll)}
                            {--order= : ID Order untuk test notifikasi produksi}';
    
    protected $description = 'Test WhatsApp API Mutekar';

    public function handle(WhatsAppService $whatsapp)
    {
        $this->info('🔧 Testing WhatsApp Mutekar API...');
        
        // Test 1: Test koneksi API
        $this->info('1. Testing API Connection...');
        $connectionTest = $whatsapp->testConnection();
        
        if ($connectionTest['connected']) {
            $this->info('✅ API Connected Successfully');
            $this->line('   Sender: ' . $connectionTest['sender']);
            $this->line('   API URL: ' . $connectionTest['api_url']);
        } else {
            $this->error('❌ API Connection Failed');
            $this->error('   Error: ' . $connectionTest['error']);
            return 1;
        }
        
        // Test 2: Jika ada phone dan message langsung
        if ($this->argument('phone') && $this->argument('message')) {
            $phone = $this->argument('phone');
            $message = $this->argument('message');
            
            $this->info("\n2. Sending Direct Message...");
            $this->line("   To: $phone");
            $this->line("   Message: $message");
            
            $result = $whatsapp->sendMessage($phone, $message);
            
            if ($result['success']) {
                $this->info('✅ Message Sent Successfully');
            } else {
                $this->error('❌ Failed to Send Message');
                $this->error('   Error: ' . ($result['error'] ?? 'Unknown error'));
            }
            
            return 0;
        }
        
        // Test 3: Jika menggunakan --stage dan --order
        if ($this->option('stage') && $this->option('order')) {
            $orderId = $this->option('order');
            $stage = $this->option('stage');
            
            $order = Order::find($orderId);
            
            if (!$order) {
                $this->error("Order dengan ID $orderId tidak ditemukan");
                return 1;
            }
            
            $this->info("\n2. Testing Production Notification...");
            $this->line("   Order ID: $orderId");
            $this->line("   Customer: {$order->customer_name}");
            $this->line("   Stage: $stage");
            
            $result = $whatsapp->sendProductionUpdate($order, $stage, 'RESI123456');
            
            if ($result['success']) {
                $this->info('✅ Production Notification Sent Successfully');
            } else {
                $this->error('❌ Failed to Send Production Notification');
            }
            
            return 0;
        }
        
        // Test 4: Default test dengan data dummy
        $this->info("\n2. Sending Test Message with Dummy Data...");
        
        $dummyOrder = (object) [
            'customer_name' => 'Ahmad Rizki',
            'customer_phone' => '081234567890',
            'product' => (object) [
                'name' => 'Kaligrafi Lampu Al-Fatihah',
                'min_quota' => 10,
                'paid_orders_count' => 7
            ]
        ];
        
        $result = $whatsapp->sendProductionUpdate($dummyOrder, 'production');
        
        if ($result['success']) {
            $this->info('✅ Test Message Sent Successfully');
            $this->table(
                ['Field', 'Value'],
                [
                    ['Status', 'Success'],
                    ['To', $result['to'] ?? 'N/A'],
                    ['Response Code', $result['status'] ?? 'N/A']
                ]
            );
        } else {
            $this->error('❌ Test Failed');
            $this->error('   Error: ' . ($result['error'] ?? 'Unknown error'));
        }
        
        return 0;
    }
}