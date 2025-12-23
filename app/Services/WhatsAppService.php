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

    /**
     * ========================================================================
     * PAYMENT NOTIFICATION METHODS (iPaymu Integration)
     * ========================================================================
     */

    /**
     * Send notification when order is created (payment pending)
     */
    public function sendOrderCreatedNotification($order): array
    {
        $customerNumber = $this->formatPhoneNumber($order->customer_phone);
        $firstName = explode(' ', $order->customer_name)[0];
        $productName = $order->product->name ?? 'Produk';
        $totalAmount = number_format($order->total_amount, 0, ',', '.');
        
        $orderUrl = url('/my/orders/' . $order->id);
        
        $message = "Assalamu'alaikum *$firstName*,\n\n";
        $message .= "Terima kasih telah melakukan Pre-Order! 🎉\n\n";
        $message .= "📦 *Detail Pesanan*\n";
        $message .= "━━━━━━━━━━━━━━━━\n";
        $message .= "• Order ID: #$order->id\n";
        $message .= "• Produk: *$productName*\n";
        $message .= "• Jumlah: $order->quantity pcs\n";
        $message .= "• Total: *Rp $totalAmount*\n\n";
        $message .= "💳 *Langkah Selanjutnya:*\n";
        $message .= "Silakan lakukan pembayaran untuk mengamankan slot PO Anda.\n\n";
        $message .= "Klik link berikut untuk melanjutkan pembayaran:\n";
        $message .= "$orderUrl\n\n";
        $message .= "⏰ Selesaikan pembayaran dalam 24 jam agar pesanan tidak dibatalkan otomatis.\n\n";
        $message .= "Jazakumullah khairan! 🙏";
        
        $result = $this->sendMessage($customerNumber, $message);
        $this->logNotification($order->id, 'order_created', $message, $result['success'] ?? false);
        
        return $result;
    }

    /**
     * Send payment reminder (belum bayar)
     */
    public function sendPaymentReminderNotification($order): array
    {
        $customerNumber = $this->formatPhoneNumber($order->customer_phone);
        $firstName = explode(' ', $order->customer_name)[0];
        $productName = $order->product->name ?? 'Produk';
        $totalAmount = number_format($order->total_amount, 0, ',', '.');
        
        $orderUrl = url('/my/orders/' . $order->id);
        
        $message = "Halo *$firstName*,\n\n";
        $message .= "🔔 *Pengingat Pembayaran*\n\n";
        $message .= "Kami menunggu pembayaran untuk:\n";
        $message .= "• Order ID: #$order->id\n";
        $message .= "• Produk: *$productName*\n";
        $message .= "• Total: *Rp $totalAmount*\n\n";
        $message .= "Yuk selesaikan pembayaran sekarang:\n";
        $message .= "$orderUrl\n\n";
        $message .= "Slot PO terbatas, jangan sampai kehabisan! ⏰\n\n";
        $message .= "Butuh bantuan? Chat admin kami.";
        
        $result = $this->sendMessage($customerNumber, $message);
        $this->logNotification($order->id, 'payment_reminder', $message, $result['success'] ?? false);
        
        return $result;
    }

    /**
     * Send notification when payment is successful
     */
    public function sendPaymentSuccessNotification($order): array
    {
        $customerNumber = $this->formatPhoneNumber($order->customer_phone);
        $firstName = explode(' ', $order->customer_name)[0];
        $productName = $order->product->name ?? 'Produk';
        $totalAmount = number_format($order->total_amount, 0, ',', '.');
        
        $message = "Alhamdulillah *$firstName*! 🎊\n\n";
        $message .= "✅ *PEMBAYARAN BERHASIL*\n";
        $message .= "━━━━━━━━━━━━━━━━\n\n";
        $message .= "Pembayaran Anda untuk *$productName* telah kami terima dengan total *Rp $totalAmount*.\n\n";
        $message .= "📋 *Status Pesanan:*\n";
        $message .= "Pesanan Anda sedang kami proses. Anda akan menerima update melalui WhatsApp saat:\n";
        $message .= "• Kuota PO terpenuhi\n";
        $message .= "• Produksi dimulai\n";
        $message .= "• Produk dalam pengiriman\n\n";
        $message .= "🎯 *Timeline Estimasi:*\n";
        $message .= "• Menunggu kuota: 7-14 hari\n";
        $message .= "• Produksi: 7-10 hari\n";
        $message .= "• Pengiriman: 2-5 hari\n\n";
        $message .= "Track pesanan Anda:\n";
        $message .= url('/my/orders/' . $order->id) . "\n\n";
        $message .= "Jazakumullah khairan atas kepercayaannya! 🙏✨\n\n";
        $message .= "_Kami akan bekerja dengan sepenuh hati untuk produk terbaik Anda._";
        
        $result = $this->sendMessage($customerNumber, $message);
        $this->logNotification($order->id, 'payment_success', $message, $result['success'] ?? false);
        
        return $result;
    }

    /**
     * Send notification when payment failed
     */
    public function sendPaymentFailedNotification($order, $reason = ''): array
    {
        $customerNumber = $this->formatPhoneNumber($order->customer_phone);
        $firstName = explode(' ', $order->customer_name)[0];
        $productName = $order->product->name ?? 'Produk';
        
        $orderUrl = url('/my/orders/' . $order->id);
        
        $message = "Halo *$firstName*,\n\n";
        $message .= "❌ *Pembayaran Gagal*\n\n";
        $message .= "Pembayaran untuk *$productName* (Order #$order->id) gagal diproses.\n\n";
        
        if ($reason) {
            $message .= "📌 Alasan: $reason\n\n";
        }
        
        $message .= "Silakan coba lagi dengan mengklik link berikut:\n";
        $message .= "$orderUrl\n\n";
        $message .= "Atau hubungi admin kami untuk bantuan lebih lanjut.\n\n";
        $message .= "Terima kasih! 🙏";
        
        $result = $this->sendMessage($customerNumber, $message);
        $this->logNotification($order->id, 'payment_failed', $message, $result['success'] ?? false);
        
        return $result;
    }

    /**
     * Send notification when payment expired
     */
    public function sendPaymentExpiredNotification($order): array
    {
        $customerNumber = $this->formatPhoneNumber($order->customer_phone);
        $firstName = explode(' ', $order->customer_name)[0];
        $productName = $order->product->name ?? 'Produk';
        
        $message = "Halo *$firstName*,\n\n";
        $message .= "⏰ *Link Pembayaran Expired*\n\n";
        $message .= "Link pembayaran untuk *$productName* (Order #$order->id) telah expired.\n\n";
        $message .= "📌 *Tindakan yang diperlukan:*\n";
        $message .= "Silakan hubungi admin kami untuk membuat link pembayaran baru atau melakukan order ulang.\n\n";
        $message .= "Contact Admin:\n";
        $message .= url('/kontak') . "\n\n";
        $message .= "Mohon maaf atas ketidaknyamanannya. 🙏";
        
        $result = $this->sendMessage($customerNumber, $message);
        $this->logNotification($order->id, 'payment_expired', $message, $result['success'] ?? false);
        
        return $result;
    }

    /**
     * Send notification when payment is refunded
     */
    public function sendPaymentRefundedNotification($order): array
    {
        $customerNumber = $this->formatPhoneNumber($order->customer_phone);
        $firstName = explode(' ', $order->customer_name)[0];
        $productName = $order->product->name ?? 'Produk';
        $totalAmount = number_format($order->total_amount, 0, ',', '.');
        
        $message = "Halo *$firstName*,\n\n";
        $message .= "💰 *Status Refund*\n\n";
        $message .= "Pembayaran untuk *$productName* (Order #$order->id) telah di-refund sebesar *Rp $totalAmount*.\n\n";
        $message .= "Dana akan kembali ke rekening/metode pembayaran Anda dalam 3-7 hari kerja.\n\n";
        $message .= "Jika ada pertanyaan, silakan hubungi admin kami.\n\n";
        $message .= "Terima kasih atas pengertiannya. 🙏";
        
        $result = $this->sendMessage($customerNumber, $message);
        $this->logNotification($order->id, 'payment_refunded', $message, $result['success'] ?? false);
        
        return $result;
    }

    /**
     * Send notification when order is confirmed (after payment)
     */
    public function sendOrderConfirmedNotification($order): array
    {
        $customerNumber = $this->formatPhoneNumber($order->customer_phone);
        $firstName = explode(' ', $order->customer_name)[0];
        $productName = $order->product->name ?? 'Produk';
        
        $message = "Barakallahu fiik *$firstName*! ✨\n\n";
        $message .= "✅ *Pesanan Dikonfirmasi*\n\n";
        $message .= "Order #$order->id untuk *$productName* telah dikonfirmasi oleh tim kami.\n\n";
        $message .= "Kami akan update progress melalui WhatsApp.\n\n";
        $message .= "Stay tuned! 🎯";
        
        $result = $this->sendMessage($customerNumber, $message);
        $this->logNotification($order->id, 'order_confirmed', $message, $result['success'] ?? false);
        
        return $result;
    }
}
