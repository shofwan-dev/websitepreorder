<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class SalesNotificationController extends Controller
{
    /**
     * Get recent sales for live notification popup
     */
    public function getRecentSales()
    {
        // Get recent paid orders (last 30 days)
        $orders = Order::with('product')
            ->where('payment_status', 'paid')
            ->where('created_at', '>=', now()->subDays(30))
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get();

        $salesData = $orders->map(function ($order) {
            // Censor customer name
            $name = $order->customer_name ?? 'Customer';
            $censoredName = $this->censorName($name);
            
            // Extract city from address
            $city = $this->extractCity($order->customer_address ?? $order->customer_city ?? '');
            
            // Get product image
            $productImages = $order->product->images ?? [];
            $productImage = null;
            
            if (!empty($productImages)) {
                if (is_array($productImages) && isset($productImages[0])) {
                    $productImage = asset('storage/' . $productImages[0]);
                } elseif (is_string($productImages)) {
                    $decoded = json_decode($productImages, true);
                    if (is_array($decoded) && isset($decoded[0])) {
                        $productImage = asset('storage/' . $decoded[0]);
                    }
                }
            }
            
            // Fallback to UI Avatars
            if (!$productImage) {
                $productName = urlencode($order->product->name ?? 'Product');
                $productImage = "https://ui-avatars.com/api/?name={$productName}&size=60&background=d4a017&color=fff";
            }

            return [
                'id' => $order->id,
                'customer_name' => $censoredName,
                'city' => $city,
                'product_name' => $order->product->name ?? 'Produk',
                'product_image' => $productImage,
                'time_ago' => $this->formatTimeAgo($order->created_at),
            ];
        })->toArray();

        return response()->json([
            'success' => true,
            'data' => $salesData
        ]);
    }

    /**
     * Censor name: "Budi Santoso" → "Bud*** San*****"
     */
    private function censorName($name)
    {
        $parts = explode(' ', $name);
        $censored = [];
        
        foreach ($parts as $part) {
            if (strlen($part) <= 2) {
                $censored[] = $part;
            } else {
                $censored[] = substr($part, 0, 3) . str_repeat('*', max(0, strlen($part) - 3));
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
            'Cirebon', 'Tasikmalaya', 'Sukabumi', 'Garut', 'Cianjur',
            'Karawang', 'Purwokerto', 'Kudus', 'Pekalongan', 'Magelang'
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
        } elseif ($diff < 1440) {
            $hours = floor($diff / 60);
            return $hours . ' jam yang lalu';
        } else {
            $days = floor($diff / 1440);
            return $days . ' hari yang lalu';
        }
    }
}
