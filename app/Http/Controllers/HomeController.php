<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Order;
use App\Models\Batch;
use Illuminate\Http\Request;


class HomeController extends Controller
{
    /**
     * Menampilkan halaman utama website PO Kaligrafi
     */
    public function index()
    {
        // Ambil produk terbaru yang sedang dalam progress
        $latestProduct = $this->getLatestProductInProgress();
        
        // Ambil semua order yang sudah terverifikasi untuk ditampilkan
        $allOrders = $this->getAllVerifiedOrders();
        
        // Ambil timeline produksi
        $productionTimeline = $this->getProductionTimeline($latestProduct);
        
        // Hitung progress kuota
        $progressData = $this->calculateProgress($latestProduct);
        
        // Ambil semua produk aktif untuk ditampilkan di grid
        $activeProducts = Product::where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('home', compact(
            'latestProduct', 
            'allOrders', 
            'productionTimeline',
            'progressData',
            'activeProducts'
        ));
    }
    
    /**
     * Mendapatkan produk utama untuk ditampilkan
     */
    private function getMainProduct()
    {
        // Cari produk yang aktif
        $product = Product::where('is_active', true)->first();
        
        // Jika tidak ada produk aktif, ambil produk pertama
        if (!$product) {
            $product = Product::first();
        }
        
        // Jika masih tidak ada produk, buat produk dummy untuk development
        if (!$product && app()->environment('local')) {
            $product = $this->createDummyProduct();
        }
        
        return $product;
    }
    
    /**
     * Mendapatkan order terbaru untuk social proof
     */
    private function getRecentOrders($product)
    {
        if (!$product) {
            return collect();
        }
        
        return Order::where('product_id', $product->id)
            ->where('payment_status', 'paid')
            ->where('is_verified', true)
            ->where('is_displayed', true)
            ->orderBy('created_at', 'desc')
            ->limit(8)
            ->get()
            ->map(function ($order) {
                return [
                    'masked_name' => $this->maskName($order->customer_name),
                    'city' => $order->customer_city,
                    'time_ago' => $order->created_at->diffForHumans(),
                    'initials' => $this->getInitials($order->customer_name)
                ];
            });
    }
    
    /**
     * Mendapatkan produk terbaru yang sedang dalam progress
     */
    private function getLatestProductInProgress()
    {
        // Cari produk yang memiliki batch dengan status 'collecting' atau 'production'
        $product = Product::whereHas('batches', function($query) {
            $query->whereIn('status', ['collecting', 'production'])
                  ->orderBy('created_at', 'desc');
        })
        ->with(['batches' => function($query) {
            $query->whereIn('status', ['collecting', 'production'])
                  ->orderBy('created_at', 'desc')
                  ->limit(1);
        }])
        ->first();
        
        // Jika tidak ada produk dengan batch aktif, ambil produk aktif pertama
        if (!$product) {
            $product = Product::where('is_active', true)->first();
        }
        
        // Jika masih tidak ada, ambil produk pertama
        if (!$product) {
            $product = Product::first();
        }
        
        // Jika masih tidak ada produk, buat produk dummy untuk development
        if (!$product && app()->environment('local')) {
            $product = $this->createDummyProduct();
        }
        
        return $product;
    }
    
    /**
     * Mendapatkan semua order yang sudah terverifikasi untuk ditampilkan
     */
    private function getAllVerifiedOrders()
    {
        return Order::where('payment_status', 'paid')
            ->where('is_verified', true)
            ->where('is_displayed', true)
            ->with('product')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($order) {
                return [
                    'product_name' => $order->product ? $order->product->name : 'Produk',
                    'masked_name' => $order->masked_name,
                    'city' => $order->customer_city,
                    'time_ago' => $order->created_at->diffForHumans(),
                    'initials' => $this->getInitials($order->customer_name)
                ];
            });
    }
    
    /**
     * Mendapatkan timeline produksi
     */
    private function getProductionTimeline($product)
    {
        if (!$product) {
            return $this->getDefaultTimeline();
        }
        
        $timeline = $product->currentTimeline;
        
        if ($timeline) {
            return [
                'current_stage' => $timeline->stage,
                'stage_label' => $timeline->stage_label,
                'stage_color' => $timeline->stage_color,
                'start_date' => $timeline->actual_start_date,
                'estimated_days' => $timeline->estimated_days,
                'notes' => $timeline->notes
            ];
        }
        
        return $this->getDefaultTimeline();
    }
    
    /**
     * Menghitung progress kuota PO
     */
    private function calculateProgress($product)
    {
        if (!$product) {
            return [
                'progress_percentage' => 0,
                'current_orders' => 0,
                'remaining_slots' => 10,
                'min_quota' => 10
            ];
        }
        
        $currentOrders = $product->paid_orders_count ?? 0;
        $minQuota = $product->min_quota ?? 10;
        $remainingSlots = max(0, $minQuota - $currentOrders);
        
        $progressPercentage = ($minQuota > 0) 
            ? min(100, ($currentOrders / $minQuota) * 100)
            : 0;
            
        return [
            'progress_percentage' => $progressPercentage,
            'current_orders' => $currentOrders,
            'remaining_slots' => $remainingSlots,
            'min_quota' => $minQuota
        ];
    }
    
    /**
     * Membuat produk dummy untuk development
     */
    private function createDummyProduct()
    {
        return (object)[
            'id' => 1,
            'name' => 'Kaligrafi Lampu Allah',
            'description' => 'Kaligrafi lampu dengan tulisan Allah yang indah, memberikan ketenangan dan keindahan dalam rumah Anda.',
            'price' => 350000,
            'min_quota' => 10,
            'current_batch' => 1,
            'images' => ['kaligrafi-allah.jpg'],
            'specifications' => [ // Pastikan ini ARRAY, bukan string
                'ukuran' => '40x40 cm',
                'bahan' => 'Kayu jati premium',
                'warna_cahaya' => 'Kuning emas hangat',
                'daya' => '5W LED',
                'garansi' => '1 tahun'
            ],
            'features' => [ // Pastikan ini ARRAY, bukan string
                'Material kayu jati berkualitas',
                'Cahaya LED hangat tidak silau',
                'Desain kaligrafi indah',
                'Pengiriman seluruh Indonesia',
                'Garansi 1 tahun',
                'Packing aman anti pecah'
            ],
            'is_active' => true
        ];
    }
    
    /**
     * Timeline default jika tidak ada data
     */
    private function getDefaultTimeline()
    {
        return [
            'current_stage' => 'po_open',
            'stage_label' => 'PO Dibuka',
            'stage_color' => 'bg-blue-100 text-blue-800',
            'stages' => [
                ['stage' => 'po_open', 'label' => 'PO Dibuka', 'active' => true],
                ['stage' => 'waiting_quota', 'label' => 'Menunggu Kuota', 'active' => false],
                ['stage' => 'production', 'label' => 'Produksi', 'active' => false],
                ['stage' => 'qc', 'label' => 'Quality Control', 'active' => false],
                ['stage' => 'packaging', 'label' => 'Pengemasan', 'active' => false],
                ['stage' => 'shipping', 'label' => 'Pengiriman', 'active' => false],
                ['stage' => 'delivered', 'label' => 'Terkirim', 'active' => false]
            ]
        ];
    }
    
    /**
     * Mask nama untuk privacy (contoh: Ahmad Rizki → Ahm*** R.)
     */
    private function maskName($name)
    {
        if (strlen($name) <= 3) {
            return $name . '***';
        }
        
        $parts = explode(' ', $name);
        $firstName = $parts[0];
        $lastInitial = count($parts) > 1 ? ' ' . substr($parts[1], 0, 1) . '.' : '';
        
        if (strlen($firstName) <= 3) {
            return $firstName . '***' . $lastInitial;
        }
        
        $maskedFirstName = substr($firstName, 0, 3) . '***';
        return $maskedFirstName . $lastInitial;
    }
    
    /**
     * Ambil inisial nama (contoh: Ahmad Rizki → AR)
     */
    private function getInitials($name)
    {
        $parts = explode(' ', $name);
        $initials = '';
        
        foreach ($parts as $part) {
            if (!empty($part)) {
                $initials .= strtoupper(substr($part, 0, 1));
                if (strlen($initials) >= 2) break;
            }
        }
        
        return $initials;
    }
    
    /**
     * Menampilkan halaman about/tentang kami
     */
    public function about()
    {
        return view('about', [
            'title' => 'Tentang PO Kaligrafi Lampu',
            'description' => 'Kami adalah pengrajin kaligrafi lampu yang berdedikasi menciptakan karya seni islami dengan kualitas terbaik.'
        ]);
    }
    
    /**
     * Menampilkan halaman cara kerja PO
     */
    public function howItWorks()
    {
        // Try to get steps from settings first
        $stepsJson = \App\Models\Setting::getValue('how_it_works_steps');
        
        if ($stepsJson) {
            $steps = json_decode($stepsJson, true);
        } else {
            // Fallback to default steps
            $steps = [
                [
                    'number' => 1,
                    'title' => 'Ikut Pre-Order',
                    'description' => 'Pilih kaligrafi yang Anda suka dan ikut pre-order dengan klik tombol "Ikut PO"',
                    'icon' => 'fas fa-cart-plus'
                ],
                [
                    'number' => 2,
                    'title' => 'Tunggu Kuota Terpenuhi',
                    'description' => 'Produksi akan dimulai setelah kuota minimal (misal: 10 pemesan) terpenuhi',
                    'icon' => 'fas fa-users'
                ],
                [
                    'number' => 3,
                    'title' => 'Proses Produksi',
                    'description' => 'Pengrajin kami akan membuat kaligrafi dengan ketelitian dan doa',
                    'icon' => 'fas fa-hammer'
                ],
                [
                    'number' => 4,
                    'title' => 'Terima Notifikasi',
                    'description' => 'Anda akan mendapat update via WhatsApp di setiap tahap produksi',
                    'icon' => 'fas fa-bell'
                ],
                [
                    'number' => 5,
                    'title' => 'Barang Dikirim',
                    'description' => 'Kaligrafi dikirim dengan pengemasan eksklusif dan aman',
                    'icon' => 'fas fa-shipping-fast'
                ],
                [
                    'number' => 6,
                    'title' => 'Terima & Nikmati',
                    'description' => 'Kaligrafi lampu siap menghiasi rumah dengan cahaya yang menenangkan',
                    'icon' => 'fas fa-home'
                ]
            ];
        }
        
        return view('how-it-works', compact('steps'));
    }

    
    /**
     * Menampilkan halaman FAQ
     */
    public function faq()
    {
        // Try to get FAQs from settings first
        $faqsJson = \App\Models\Setting::getValue('faq_items');
        
        if ($faqsJson) {
            $faqs = json_decode($faqsJson, true);
        } else {
            // Fallback to default FAQs
            $faqs = [
                [
                    'question' => 'Apa itu Pre-Order (PO)?',
                    'answer' => 'Pre-Order adalah sistem pemesanan di mana produk akan diproduksi setelah mencapai kuota minimal pemesan. Ini memastikan kualitas produksi tetap terjaga dan menghindari overproduksi.'
                ],
                [
                    'question' => 'Berapa lama waktu produksi?',
                    'answer' => 'Setelah kuota terpenuhi, proses produksi membutuhkan 7-10 hari kerja ditambah 2-5 hari untuk pengiriman. Total estimasi 9-15 hari kerja.'
                ],
                [
                    'question' => 'Apakah bisa request desain custom?',
                    'answer' => 'Ya, untuk batch tertentu kami membuka customisasi. Silakan chat admin melalui WhatsApp untuk konsultasi desain.'
                ],
                [
                    'question' => 'Bagaimana sistem pembayarannya?',
                    'answer' => 'Pembayaran dilakukan melalui iPaymu yang aman dan terpercaya. Setelah pembayaran diverifikasi, nama Anda akan muncul di daftar PO.'
                ],
                [
                    'question' => 'Apakah ada garansi?',
                    'answer' => 'Ya, semua produk kami bergaransi 1 tahun untuk kerusakan non-kecelakaan. Untuk klaim garansi, silakan hubungi admin.'
                ],
                [
                    'question' => 'Bisa dikirim ke mana saja?',
                    'answer' => 'Kami bisa kirim ke seluruh Indonesia via ekspedisi terpercaya. Biaya pengiriman disesuaikan dengan kota tujuan.'
                ]
            ];
        }
        
        return view('faq', compact('faqs'));
    }

    
    /**
     * Menampilkan halaman kontak
     */
    public function contact()
    {
        return view('contact', [
            'whatsapp' => '6281234567890',
            'email' => 'admin@pokaligrafi.com',
            'instagram' => '@pokaligrafi',
            'address' => 'Jl. Pengrajin No. 123, Yogyakarta',
            'business_hours' => 'Senin - Jumat: 09:00 - 17:00 WIB'
        ]);
    }
    
    /**
     * Menampilkan halaman detail produk
     */
    public function productDetail(Product $product)
    {
        // Load relationships
        $product->load(['batches' => function($query) {
            $query->whereIn('status', ['collecting', 'production'])
                  ->orderBy('created_at', 'desc')
                  ->limit(1);
        }]);
        
        // Get active batch
        $activeBatch = $product->batches->first();
        
        // Calculate progress
        $progressData = $this->calculateProgress($product);
        
        // Get recent orders for this product
        $recentOrders = Order::where('product_id', $product->id)
            ->where('payment_status', 'paid')
            ->where('is_verified', true)
            ->where('is_displayed', true)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($order) {
                return [
                    'masked_name' => $order->masked_name,
                    'city' => $order->customer_city,
                    'time_ago' => $order->created_at->diffForHumans(),
                    'initials' => $this->getInitials($order->customer_name)
                ];
            });
        
        return view('product-detail', compact('product', 'activeBatch', 'progressData', 'recentOrders'));
    }
}