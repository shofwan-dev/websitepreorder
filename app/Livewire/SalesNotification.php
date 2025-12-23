<?php

namespace App\Livewire;

use App\Models\Order;
use Livewire\Component;

class SalesNotification extends Component
{
    public $isVisible = false;
    public $currentSale = null;
    public $salesData = [];
    public $currentIndex = 0;

    public function mount()
    {
        $this->loadSalesData();
    }

    public function loadSalesData()
    {
        // Get recent paid orders (last 30 days)
        $orders = Order::with('product')
            ->where('payment_status', 'paid')
            ->where('paid_at', '>=', now()->subDays(30))
            ->orderBy('paid_at', 'desc')
            ->limit(20)
            ->get();

        $this->salesData = $orders->map(function ($order) {
            // Censor customer name (show first 3 chars + asterisks)
            $name = $order->customer_name ?? 'Customer';
            $censoredName = $this->censorName($name);
            
            // Extract city from address
            $city = $this->extractCity($order->customer_address ?? '');
            
            // Get product image or default
            $productImages = $order->product->images ?? [];
            $productImage = !empty($productImages) && isset($productImages[0]) 
                ? asset('storage/' . $productImages[0]) 
                : asset('images/default-product.png');

            return [
                'id' => $order->id,
                'customer_name' => $censoredName,
                'city' => $city,
                'product_name' => $order->product->name ?? 'Produk',
                'product_image' => $productImage,
                'time_ago' => $this->formatTimeAgo($order->paid_at),
            ];
        })->toArray();
    }

    /**
     * Censor name: "Budi Santoso" → "Bud*** S******"
     */
    private function censorName($name)
    {
        $parts = explode(' ', $name);
        $censored = [];
        
        foreach ($parts as $part) {
            if (strlen($part) <= 2) {
                $censored[] = $part;
            } else {
                $censored[] = substr($part, 0, 3) . str_repeat('*', strlen($part) - 3);
            }
        }
        
        return implode(' ', $censored);
    }

    /**
     * Extract city from address
     */
    private function extractCity($address)
    {
        $cities = [
            'Jakarta', 'Surabaya', 'Bandung', 'Medan', 'Semarang',
            'Makassar', 'Palembang', 'Tangerang', 'Depok', 'Bekasi',
            'Bogor', 'Yogyakarta', 'Malang', 'Solo', 'Batam',
            'Pekanbaru', 'Bandar Lampung', 'Padang', 'Denpasar', 'Bali',
            'Cirebon', 'Tasikmalaya', 'Sukabumi', 'Garut', 'Cianjur'
        ];
        
        foreach ($cities as $city) {
            if (stripos($address, $city) !== false) {
                return $city;
            }
        }
        
        return 'Indonesia';
    }

    /**
     * Format time ago in Indonesian
     */
    private function formatTimeAgo($datetime)
    {
        if (!$datetime) return 'baru saja';
        
        $diff = now()->diffInMinutes($datetime);
        
        if ($diff < 1) {
            return 'baru saja';
        } elseif ($diff < 60) {
            return $diff . ' menit yang lalu';
        } elseif ($diff < 1440) { // 24 hours
            $hours = floor($diff / 60);
            return $hours . ' jam yang lalu';
        } else {
            $days = floor($diff / 1440);
            return $days . ' hari yang lalu';
        }
    }

    public function showNotification()
    {
        if (count($this->salesData) > 0) {
            $this->currentSale = $this->salesData[$this->currentIndex];
            $this->isVisible = true;
            
            // Move to next item
            $this->currentIndex = ($this->currentIndex + 1) % count($this->salesData);
        }
    }

    public function hideNotification()
    {
        $this->isVisible = false;
    }

    public function render()
    {
        return view('livewire.sales-notification');
    }
}
