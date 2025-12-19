<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Setting;

class WhatsAppService
{
    protected $apiKey;
    protected $sender;
    protected $baseUrl;

    public function __construct()
    {
        // Prioritas: Database settings -> Config -> Default
        $this->apiKey = Setting::getValue('whatsapp_api_key') ?: config('services.whatsapp.api_key');
        $this->sender = Setting::getValue('whatsapp_sender') ?: config('services.whatsapp.sender');
        $this->baseUrl = Setting::getValue('whatsapp_endpoint') ?: 'https://wa.mutekar.com/send-message';
    }

    /**
     * Kirim pesan WhatsApp ke pelanggan
     */
    public function sendMessage($phoneNumber, $message): array  // ✅ TAMBAHKAN RETURN TYPE
    {
        $formattedNumber = $this->formatPhoneNumber($phoneNumber);
        
        try {
            // Coba metode POST terlebih dahulu
            $response = Http::post($this->baseUrl, [
                'api_key' => $this->apiKey,
                'sender' => $this->sender,
                'number' => $formattedNumber,
                'message' => $message
            ]);

            // Jika POST gagal, coba GET
            if ($response->failed()) {
                $getUrl = $this->baseUrl . '?' . http_build_query([
                    'api_key' => $this->apiKey,
                    'sender' => $this->sender,
                    'number' => $formattedNumber,
                    'message' => $message
                ]);
                
                $response = Http::get($getUrl);
            }

            Log::info('WhatsApp API Response', [
                'to' => $formattedNumber,
                'message' => substr($message, 0, 50) . '...',
                'status' => $response->status(),
                'response' => $response->json()
            ]);

            return [  // ✅ PASTIKAN ADA RETURN
                'success' => $response->successful(),
                'status' => $response->status(),
                'data' => $response->json(),
                'to' => $formattedNumber
            ];

        } catch (\Exception $e) {
            Log::error('WhatsApp API Error: ' . $e->getMessage(), [
                'to' => $formattedNumber,
                'error' => $e->getTraceAsString()
            ]);
            
            return [  // ✅ PASTIKAN ADA RETURN DI CATCH BLOCK
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Kirim notifikasi update produksi
     */
    public function sendProductionUpdate($order, $stage, $additionalInfo = ''): array  // ✅ TAMBAHKAN RETURN TYPE
    {
        $customerNumber = $this->formatPhoneNumber($order->customer_phone);
        
        $message = $this->generateProductionMessage(
            $order->customer_name,
            $order->product->name ?? 'Kaligrafi Lampu',
            $stage,
            $additionalInfo,
            $order
        );

        // ✅ KIRIM PESAN DAN RETURN HASILNYA
        $result = $this->sendMessage($customerNumber, $message);

        // Simpan log pengiriman
        $this->logNotification($order->id, $stage, $message, $result['success'] ?? false);

        return $result;  // ✅ RETURN HASIL
    }

    /**
     * Format nomor telepon Indonesia ke format 62xxx
     */
    private function formatPhoneNumber($phone): string  // ✅ TAMBAHKAN RETURN TYPE
    {
        if (empty($phone)) {
            throw new \Exception('Nomor telepon kosong');
        }
        
        // Hapus semua karakter non-digit
        $phone = preg_replace('/[^0-9]/', '', $phone);
        
        if (empty($phone)) {
            throw new \Exception('Nomor telepon tidak valid: ' . $phone);
        }
        
        // Jika diawali 0, ganti dengan 62
        if (substr($phone, 0, 1) === '0') {
            $phone = '62' . substr($phone, 1);
        }
        
        // Jika diawali 8 (tanpa 0), tambah 62
        if (substr($phone, 0, 1) === '8') {
            $phone = '62' . $phone;
        }
        
        // Jika sudah diawali 62, pastikan panjangnya minimal 10
        if (substr($phone, 0, 2) === '62') {
            if (strlen($phone) < 10) {
                throw new \Exception('Nomor telepon terlalu pendek: ' . $phone);
            }
        }
        
        return $phone;
    }

    /**
     * Generate pesan berdasarkan tahap produksi
     */
    private function generateProductionMessage($customerName, $productName, $stage, $additionalInfo, $order): string  // ✅ TAMBAHKAN RETURN TYPE
    {
        $firstName = explode(' ', $customerName)[0];
        $trackingUrl = url('/tracking/' . ($order->id ?? ''));
        
        $messages = [
            'po_open' => "Assalamu'alaikum $firstName,\n\nPO *$productName* telah dibuka! Segera lakukan pembayaran untuk mengamankan slot Anda.\n\nSalurkan kepercayaan Anda pada karya lokal ✨",
            
            'waiting_quota' => "Halo $firstName,\n\nPembayaran untuk *$productName* telah kami terima. Saat ini kami sedang menunggu kuota minimal terpenuhi.\n\nSabarlah, kebaikan butuh proses 🌱",
            
            'production' => "Alhamdulillah $firstName!\n\nKuota *$productName* telah terpenuhi! 🎉\nProses produksi akan dimulai dalam 1-2 hari ke depan.\n\nTim pengrajin kami akan bekerja dengan penuh ketelitian.",
            
            'qc' => "$firstName, kabar baik!\n\n*$productName* sedang melalui tahap Quality Control. Kami memastikan setiap detail sempurna sebelum dikirim ke Anda.\n\nEstimasi: 2-3 hari lagi",
            
            'packaging' => "Halo $firstName,\n\n*$productName* telah selesai diproduksi dan sedang dalam proses pengemasan eksklusif.\n\nKami akan kirimkan foto sebelum dikirim 📦",
            
            'shipping' => "*$productName* SUDAH DIKIRIM!*\n\nNo Resi: $additionalInfo\nEstimasi tiba: 2-5 hari kerja\n\nLink tracking: $trackingUrl\n\nJazakumullah khairan atas kepercayaannya 🙏",
            
            'delivered' => "Alhamdulillah $firstName,\n\n*$productName* telah sampai! Semoga membawa keberkahan dan ketenangan di rumah Anda.\n\nJangan lupa share foto kaligrafinya ya! 📸\n\nReview Anda sangat berarti bagi kami 💫"
        ];

        return $messages[$stage] ?? "Update produksi $productName: " . $stage;
    }

    /**
     * Simpan log notifikasi ke database
     */
    private function logNotification($orderId, $stage, $message, $success): void  // ✅ TAMBAHKAN RETURN TYPE
    {
        try {
            if (class_exists('\App\Models\NotificationLog')) {
                \App\Models\NotificationLog::create([
                    'order_id' => $orderId,
                    'type' => 'whatsapp',
                    'stage' => $stage,
                    'message' => $message,
                    'status' => $success ? 'sent' : 'failed',
                    'sent_at' => now()
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Gagal menyimpan log notifikasi: ' . $e->getMessage());
        }
    }

    /**
     * Test koneksi API
     */
    public function testConnection(): array  // ✅ TAMBAHKAN RETURN TYPE
    {
        try {
            $testMessage = 'Test koneksi API WhatsApp PO Kaligrafi';
            
            // Coba ping API tanpa mengirim pesan
            $response = Http::timeout(10)->get($this->baseUrl);
            
            return [
                'connected' => $response->status() !== 0,
                'api_url' => $this->baseUrl,
                'sender' => $this->sender,
                'status' => $response->status()
            ];
        } catch (\Exception $e) {
            return [
                'connected' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Bulk send messages (untuk broadcast)
     */
    public function sendBulkMessages(array $phoneNumbers, $message): array  // ✅ TAMBAHKAN RETURN TYPE
    {
        $results = [];
        
        foreach ($phoneNumbers as $phone) {
            $results[$phone] = $this->sendMessage($phone, $message);
            // Delay 1 detik antar pesan untuk hindari rate limit
            sleep(1);
        }
        
        return $results;
    }
}