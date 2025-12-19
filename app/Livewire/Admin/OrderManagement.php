<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductionTimeline;

class OrderManagement extends Component
{
    public $orders;
    public $selectedOrders = [];
    public $productId;
    
    public function mount()
    {
        $this->loadOrders();
    }
    
    public function loadOrders()
    {
        $this->orders = Order::with('product')
            ->orderBy('created_at', 'desc')
            ->paginate(20);
    }
    
    public function toggleDisplay($orderId)
    {
        $order = Order::find($orderId);
        $order->update(['is_displayed' => !$order->is_displayed]);
        
        $this->dispatch('socialProofUpdated');
    }
    
    public function updateProductionStage($productId, $stage)
    {
        // ✅ AMBIL PRODUCT BERDASARKAN ID
        $product = Product::find($productId);
        
        if (!$product) {
            session()->flash('error', 'Produk tidak ditemukan');
            return;
        }
        
        // ✅ UPDATE PRODUCTION TIMELINE
        $timeline = ProductionTimeline::where('product_id', $productId)
            ->where('batch_number', $product->current_batch)
            ->first();
            
        // Jika timeline belum ada, buat baru
        if (!$timeline) {
            $timeline = ProductionTimeline::create([
                'product_id' => $productId,
                'batch_number' => $product->current_batch,
                'stage' => $stage
            ]);
        } else {
            $timeline->update(['stage' => $stage]);
        }
        
        $this->dispatch('timelineUpdated');
        
        session()->flash('message', 'Tahap produksi diperbarui!');
    }
    
    public function render()
    {
        return view('livewire.admin.order-management', [
            'orders' => $this->orders
        ]);
    }
}