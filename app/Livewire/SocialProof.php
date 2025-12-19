<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Product;
use App\Models\Order;
class SocialProof extends Component
{
    public $productId;
    public $recentBuyers;
    
    public function mount($productId)
    {
        $this->productId = $productId;
        $this->loadRecentBuyers();
    }
    
    public function loadRecentBuyers()
    {
        $this->recentBuyers = Order::where('product_id', $this->productId)
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
                    'time_ago' => $order->created_at->diffForHumans()
                ];
            });
    }
    
    public function render()
    {
        return view('livewire.social-proof');
    }
}