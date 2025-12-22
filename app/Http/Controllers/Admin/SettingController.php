<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    /**
     * Display website settings page
     */
    public function website()
    {
        $settings = Setting::getGroup('website');
        
        return view('admin.settings.website', [
            'settings' => $settings
        ]);
    }

    /**
     * Update website settings
     */
    public function updateWebsite(Request $request)
    {
        $validated = $request->validate([
            'site_name' => ['required', 'string', 'max:255'],
            'tagline' => ['nullable', 'string', 'max:500'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'whatsapp' => ['nullable', 'string', 'max:50'],
            'instagram' => ['nullable', 'string', 'max:100'],
            'facebook' => ['nullable', 'string', 'max:255'],
            'twitter' => ['nullable', 'string', 'max:100'],
            'business_hours' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:1000'],
            'logo' => ['nullable', 'image', 'mimes:png,jpg,jpeg,svg,ico,webp', 'max:2048'],
        ]);

        // Save text settings
        Setting::setValue('site_name', $validated['site_name'], 'website');
        Setting::setValue('tagline', $validated['tagline'] ?? '', 'website');
        Setting::setValue('email', $validated['email'] ?? '', 'website');
        Setting::setValue('phone', $validated['phone'] ?? '', 'website');
        Setting::setValue('whatsapp', $validated['whatsapp'] ?? '', 'website');
        Setting::setValue('instagram', $validated['instagram'] ?? '', 'website');
        Setting::setValue('facebook', $validated['facebook'] ?? '', 'website');
        Setting::setValue('twitter', $validated['twitter'] ?? '', 'website');
        Setting::setValue('business_hours', $validated['business_hours'] ?? '', 'website');
        Setting::setValue('address', $validated['address'] ?? '', 'website');

        // Handle logo upload
        if ($request->hasFile('logo')) {
            $logo = $request->file('logo');
            
            // Debug logging
            \Log::info('Logo upload attempt', [
                'isValid' => $logo->isValid(),
                'size' => $logo->getSize(),
                'realPath' => $logo->getRealPath(),
                'mimeType' => $logo->getMimeType(),
                'clientOriginalName' => $logo->getClientOriginalName(),
                'extension' => $logo->getClientOriginalExtension(),
                'tmpName' => $logo->getPathname()
            ]);
            
            // Relaxed validation - just check if file is valid and is an image
            if ($logo->isValid() && $logo->getSize() > 0) {
                $mimeType = $logo->getMimeType();
                $extension = strtolower($logo->getClientOriginalExtension());
                
                // Accept common image formats
                $allowedMimes = [
                    'image/png', 'image/jpeg', 'image/jpg', 'image/pjpeg',
                    'image/svg+xml', 'image/svg', 
                    'image/x-icon', 'image/vnd.microsoft.icon',
                    'image/webp', 'image/gif'
                ];
                
                $allowedExtensions = ['png', 'jpg', 'jpeg', 'svg', 'ico', 'webp', 'gif'];
                
                // Check either MIME type or extension
                if (in_array($mimeType, $allowedMimes) || in_array($extension, $allowedExtensions)) {
                    try {
                        // Generate unique filename
                        $filename = time() . '_' . uniqid() . '.' . $extension;
                        $path = 'logos/' . $filename;
                        
                        // Use alternative method: read file content and put to storage
                        // This bypasses the getRealPath issue
                        $fileContent = file_get_contents($logo->getPathname());
                        
                        if ($fileContent === false) {
                            \Log::error('Failed to read uploaded file content');
                            return back()->withErrors(['logo' => 'Gagal membaca file yang diupload.']);
                        }
                        
                        // Store to public disk
                        $stored = \Storage::disk('public')->put($path, $fileContent);
                        
                        if ($stored) {
                            Setting::setValue('site_logo', $path, 'website');
                            \Log::info('Logo uploaded successfully', ['path' => $path]);
                        } else {
                            \Log::error('Failed to store logo file to disk');
                            return back()->withErrors(['logo' => 'Gagal menyimpan logo ke storage.']);
                        }
                    } catch (\Exception $e) {
                        \Log::error('Logo upload exception: ' . $e->getMessage(), [
                            'trace' => $e->getTraceAsString()
                        ]);
                        return back()->withErrors(['logo' => 'Terjadi kesalahan saat upload logo: ' . $e->getMessage()]);
                    }
                } else {
                    \Log::warning('File rejected - invalid type', [
                        'mimeType' => $mimeType,
                        'extension' => $extension
                    ]);
                    return back()->withErrors(['logo' => 'Format file tidak didukung. MIME: ' . $mimeType . ', Extension: ' . $extension]);
                }
            } else {
                \Log::warning('File rejected - invalid or empty', [
                    'isValid' => $logo->isValid(),
                    'size' => $logo->getSize()
                ]);
                return back()->withErrors(['logo' => 'File tidak valid atau kosong. Size: ' . $logo->getSize() . ' bytes']);
            }
        }

        // Handle logo removal
        if ($request->has('remove_logo') && $request->remove_logo == '1') {
            Setting::setValue('site_logo', '', 'website');
        }

        return back()->with('success', 'Pengaturan website berhasil disimpan.');
    }

    /**
     * Display WhatsApp settings page
     */
    public function whatsapp()
    {
        $settings = Setting::getGroup('whatsapp');
        
        return view('admin.settings.whatsapp', [
            'settings' => $settings
        ]);
    }

    /**
     * Update WhatsApp settings
     */
    public function updateWhatsapp(Request $request)
    {
        $validated = $request->validate([
            'api_key' => ['nullable', 'string', 'max:255'],
            'sender' => ['nullable', 'string', 'max:20'],
            'endpoint' => ['nullable', 'url', 'max:500'],
        ]);

        // Save each setting
        Setting::setValue('whatsapp_api_key', $validated['api_key'] ?? '', 'whatsapp');
        Setting::setValue('whatsapp_sender', $validated['sender'] ?? '', 'whatsapp');
        Setting::setValue('whatsapp_endpoint', $validated['endpoint'] ?? '', 'whatsapp');

        return back()->with('success', 'Pengaturan WhatsApp berhasil disimpan.');
    }

    /**
     * Display payment settings page
     */
    public function payment()
    {
        return view('admin.settings.payment');
    }

    /**
     * Update payment settings
     */
    public function updatePayment(Request $request)
    {
        $validated = $request->validate([
            'ipaymu_va' => ['required', 'string', 'max:50'],
            'ipaymu_api_key' => ['required', 'string', 'max:255'],
            'ipaymu_environment' => ['required', 'in:sandbox,production'],
        ]);

        // Update .env file
        $this->updateEnvFile([
            'IPAYMU_VA' => $validated['ipaymu_va'],
            'IPAYMU_API_KEY' => $validated['ipaymu_api_key'],
            'IPAYMU_ENVIRONMENT' => $validated['ipaymu_environment'],
        ]);

        // Clear config cache
        \Artisan::call('config:clear');

        return back()->with('payment_success', 'Pengaturan iPaymu berhasil disimpan.');
    }

    /**
     * Test iPaymu connection
     */
    public function testPayment()
    {
        try {
            $ipaymu = new \App\Services\IPaymuService();
            $result = $ipaymu->testConnection();

            if ($result['success']) {
                return back()->with('payment_success', 'Koneksi ke iPaymu berhasil! Status: ' . $result['status_code']);
            } else {
                return back()->with('payment_error', 'Koneksi gagal: ' . $result['message']);
            }
        } catch (\Exception $e) {
            return back()->with('payment_error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Update .env file with new values
     */
    private function updateEnvFile(array $data)
    {
        $envFile = base_path('.env');
        $envContent = file_get_contents($envFile);

        foreach ($data as $key => $value) {
            // Escape special characters in value
            $escapedValue = preg_replace('/[^A-Za-z0-9_\-.]/', '', $value);
            
            // Check if key exists
            if (preg_match("/^{$key}=.*/m", $envContent)) {
                // Update existing key
                $envContent = preg_replace(
                    "/^{$key}=.*/m",
                    "{$key}={$escapedValue}",
                    $envContent
                );
            } else {
                // Add new key
                $envContent .= "\n{$key}={$escapedValue}";
            }
        }

        file_put_contents($envFile, $envContent);
    }


    /**
     * Display content management page
     */
    public function content()
    {
        $settings = Setting::whereIn('group', ['content', 'website'])->pluck('value', 'key')->toArray();
        
        // Populate default content jika belum ada
        if (empty($settings['refund_policy_content'])) {
            $settings['refund_policy_content'] = $this->getDefaultRefundPolicy();
        }
        
        if (empty($settings['terms_conditions_content'])) {
            $settings['terms_conditions_content'] = $this->getDefaultTermsConditions();
        }
        
        return view('admin.settings.content', [
            'settings' => $settings
        ]);
    }
    
    /**
     * Get default refund policy content (generic)
     */
    private function getDefaultRefundPolicy()
    {
        $siteName = Setting::getValue('site_name') ?? 'Pre-Order';
        
        return <<<HTML
<h4>1. Kebijakan Umum</h4>
<p>{$siteName} berkomitmen memberikan layanan terbaik kepada pelanggan. Kebijakan refund ini dibuat untuk melindungi hak pelanggan sekaligus menjaga keberlanjutan bisnis Pre-Order kami.</p>

<h4>2. Ketentuan Refund</h4>
<div class="alert alert-info">
    <strong>Penting!</strong> Karena sifat sistem Pre-Order, refund hanya dapat dilakukan dalam kondisi tertentu.
</div>

<h5>2.1. Refund Sebelum Produksi Dimulai</h5>
<ul>
    <li>Pembatalan dapat dilakukan maksimal <strong>24 jam setelah pembayaran</strong> dengan potongan biaya administrasi 10%</li>
    <li>Jika kuota PO belum terpenuhi, refund 100% tanpa potongan</li>
    <li>Setelah kuota terpenuhi dan produksi dimulai, pembatalan tidak dapat dilakukan</li>
</ul>

<h5>2.2. Refund Setelah Produksi</h5>
<ul>
    <li>Refund hanya diberikan jika terjadi <strong>cacat produksi</strong> yang dibuktikan dengan foto/video</li>
    <li>Kerusakan akibat pengiriman akan ditangani oleh pihak ekspedisi</li>
    <li>Ketidaksesuaian warna akibat perbedaan layar <strong>bukan</strong> alasan refund</li>
</ul>

<h4>3. Cara Mengajukan Refund</h4>
<ol>
    <li><strong>Hubungi Admin</strong> - Hubungi kami via WhatsApp dengan menyertakan nomor pesanan</li>
    <li><strong>Kirim Bukti</strong> - Sertakan foto/video produk jika mengklaim cacat produksi</li>
    <li><strong>Verifikasi</strong> - Tim kami akan memverifikasi dalam 1-3 hari kerja</li>
    <li><strong>Proses Refund</strong> - Dana dikembalikan dalam 7-14 hari kerja setelah disetujui</li>
</ol>

<h4>4. Metode Pengembalian</h4>
<ul>
    <li>Transfer bank ke rekening yang sama dengan pengirim (verifikasi required)</li>
    <li>Saldo payment gateway (instant, tanpa biaya transfer)</li>
    <li>Voucher untuk pesanan berikutnya dengan bonus 5%</li>
</ul>

<h4>5. Kondisi Khusus</h4>
<div class="alert alert-warning">
    <h6>Tidak Ada Refund Untuk:</h6>
    <ul>
        <li>Perubahan pikiran/tidak jadi setelah produksi dimulai</li>
        <li>Kesalahan input alamat oleh pelanggan</li>
        <li>Keterlambatan pengiriman di luar kendali kami (force majeure)</li>
        <li>Custom design yang sudah disetujui pelanggan</li>
    </ul>
</div>

<div class="alert alert-success mt-4">
    <strong>Catatan:</strong> Kebijakan ini dapat berubah sewaktu-waktu. Perubahan akan diinformasikan melalui email atau WhatsApp yang terdaftar.
</div>
HTML;
    }
    
    /**
     * Get default terms and conditions content (generic)
     */
    private function getDefaultTermsConditions()
    {
        $siteName = Setting::getValue('site_name') ?? 'Pre-Order';
        
        return <<<HTML
<div class="alert alert-primary mb-4">
    Dengan menggunakan layanan kami, Anda dianggap telah membaca, memahami, dan menyetujui seluruh syarat dan ketentuan berikut.
</div>

<h4>1. Definisi</h4>
<ul>
    <li><strong>"Kami"</strong> merujuk pada {$siteName}, platform Pre-Order produk.</li>
    <li><strong>"Pelanggan"</strong> adalah individu atau badan yang menggunakan layanan kami.</li>
    <li><strong>"Pre-Order (PO)"</strong> adalah sistem pemesanan produk yang akan diproduksi setelah mencapai kuota minimum.</li>
    <li><strong>"Produk"</strong> adalah barang yang ditawarkan melalui sistem Pre-Order.</li>
</ul>

<h4>2. Ketentuan Umum</h4>
<h5>2.1. Pendaftaran Akun</h5>
<ul>
    <li>Pelanggan wajib mendaftar dengan informasi yang benar dan valid</li>
    <li>Satu nomor WhatsApp hanya dapat terdaftar untuk satu akun</li>
    <li>Pelanggan bertanggung jawab menjaga kerahasiaan akun dan password</li>
    <li>Kami berhak menonaktifkan akun yang memberikan informasi palsu</li>
</ul>

<h5>2.2. Verifikasi Identitas</h5>
<ul>
    <li>Untuk keamanan transaksi, kami dapat meminta verifikasi identitas tambahan</li>
    <li>Verifikasi diperlukan untuk pembayaran dengan nilai tertentu</li>
    <li>Data pribadi pelanggan akan dilindungi sesuai kebijakan privasi</li>
</ul>

<h4>3. Sistem Pre-Order</h4>
<h5>3.1. Mekanisme Pre-Order</h5>
<ul>
    <li>Produk akan diproduksi setelah kuota minimum terpenuhi</li>
    <li>Estimasi waktu produksi adalah 7-10 hari kerja setelah kuota terpenuhi</li>
    <li>Kami berhak menutup Pre-Order jika kuota maksimum tercapai</li>
    <li>Jika kuota tidak terpenuhi dalam 30 hari, dana akan dikembalikan 100%</li>
</ul>

<h5>3.2. Harga dan Pembayaran</h5>
<ul>
    <li>Harga yang tertera sudah termasuk pajak (jika ada)</li>
    <li>Ongkos kirim dihitung saat checkout berdasarkan alamat tujuan</li>
    <li>Pembayaran dilakukan melalui payment gateway resmi (iPaymu atau metode lain yang tersedia)</li>
    <li>Bukti pembayaran otomatis tersimpan dalam sistem</li>
    <li>Kami tidak bertanggung jawab atas biaya transfer antar bank</li>
</ul>

<h4>4. Pengiriman</h4>
<h5>4.1. Estimasi Waktu</h5>
<ul>
    <li>Pengiriman dilakukan setelah produk selesai dan lolos QC</li>
    <li>Estimasi pengiriman 2-5 hari kerja (tergantung lokasi)</li>
    <li>Keterlambatan di luar kendali kami tidak menjadi alasan refund</li>
</ul>

<h5>4.2. Tanggung Jawab Pengiriman</h5>
<ul>
    <li>Produk diasuransikan untuk melindungi dari kerusakan pengiriman</li>
    <li>Pelanggan wajib memeriksa kondisi paket saat diterima</li>
    <li>Kerusakan akibat pengiriman harus dilaporkan dalam 1x24 jam</li>
    <li>Foto unboxing sangat disarankan sebagai bukti jika terjadi masalah</li>
</ul>

<h4>5. Garansi Produk</h4>
<div class="alert alert-info">
    <h6>Cakupan Garansi (1 Tahun):</h6>
    <ul>
        <li>Cacat produksi pada bahan</li>
        <li>Kerusakan komponen dalam kondisi pemakaian normal</li>
        <li>Kualitas produk yang tidak sesuai standar</li>
    </ul>
</div>

<div class="alert alert-warning">
    <h6>Tidak Termasuk Garansi:</h6>
    <ul>
        <li>Kerusakan akibat kelalaian pengguna</li>
        <li>Modifikasi atau perbaikan oleh pihak ketiga</li>
        <li>Pemakaian di luar kondisi normal</li>
        <li>Kerusakan akibat bencana alam</li>
    </ul>
</div>

<h4>6. Privasi dan Data Pribadi</h4>
<ul>
    <li>Data pribadi pelanggan akan dijaga kerahasiaannya</li>
    <li>Data hanya digunakan untuk keperluan transaksi dan komunikasi</li>
    <li>Kami tidak akan membagikan data kepada pihak ketiga tanpa persetujuan</li>
    <li>Pelanggan dapat meminta penghapusan data sesuai regulasi yang berlaku</li>
</ul>

<h4>7. Force Majeure</h4>
<p>Kami tidak bertanggung jawab atas keterlambatan atau kegagalan dalam memenuhi kewajiban yang disebabkan oleh kondisi di luar kendali kami, termasuk bencana alam, pandemi, perang, atau gangguan infrastruktur.</p>

<h4>8. Hukum yang Berlaku</h4>
<p>Syarat dan ketentuan ini tunduk pada hukum Republik Indonesia. Setiap perselisihan akan diselesaikan melalui musyawarah, atau melalui jalur hukum yang berlaku di Indonesia.</p>

<div class="alert alert-info mt-4">
    Kami berhak mengubah syarat dan ketentuan ini sewaktu-waktu. Perubahan akan diinformasikan melalui website dan notifikasi kepada pelanggan.
</div>
HTML;
    }


    /**
     * Update content (About, How It Works, FAQ)
     */
    public function updateContent(Request $request)
    {
        $section = $request->input('section');

        switch ($section) {
            case 'about':
                $validated = $request->validate([
                    'about_title' => 'nullable|string|max:255',
                    'about_description' => 'nullable|string',
                    'about_vision' => 'nullable|string',
                    'about_mission' => 'nullable|string',
                ]);

                foreach ($validated as $key => $value) {
                    Setting::setValue($key, $value ?? '', 'content');
                }
                break;

            case 'how_it_works':
                $steps = $request->input('steps', []);
                
                // Reindex and add number field
                $formattedSteps = [];
                foreach (array_values($steps) as $index => $step) {
                    $formattedSteps[] = [
                        'number' => $index + 1,
                        'title' => $step['title'] ?? '',
                        'description' => $step['description'] ?? '',
                        'icon' => $step['icon'] ?? 'fas fa-star'
                    ];
                }
                
                Setting::setValue('how_it_works_steps', json_encode($formattedSteps), 'content');
                break;

            case 'faq':
                $faqs = $request->input('faqs', []);
                
                // Reindex FAQ array
                $formattedFaqs = [];
                foreach (array_values($faqs) as $faq) {
                    $formattedFaqs[] = [
                        'question' => $faq['question'] ?? '',
                        'answer' => $faq['answer'] ?? ''
                    ];
                }
                
                Setting::setValue('faq_items', json_encode($formattedFaqs), 'content');
                break;

            case 'refund_policy':
                $validated = $request->validate([
                    'refund_policy_content' => 'nullable|string',
                ]);
                
                Setting::setValue('refund_policy_content', $validated['refund_policy_content'] ?? '', 'content');
                break;

            case 'terms_conditions':
                $validated = $request->validate([
                    'terms_conditions_content' => 'nullable|string',
                ]);
                
                Setting::setValue('terms_conditions_content', $validated['terms_conditions_content'] ?? '', 'content');
                break;

            default:
                return back()->withErrors(['section' => 'Invalid section']);
        }

        return back()->with('success', 'Konten berhasil diperbarui!');
    }
}
