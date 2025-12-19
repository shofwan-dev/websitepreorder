<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Product;

class PoProgress extends Component
{
    public $productId;
    public $progress = 0;
    public $currentOrders = 0;
    public $remainingSlots = 0;
    public $productName = 'Kaligrafi Lampu'; // Default name
    
    protected $listeners = ['orderUpdated' => 'updateProgress'];
    
    public function mount($productId)
    {
        $this->productId = $productId;
        $this->updateProgress();
    }
    
    public function updateProgress()
    {
        $product = Product::find($this->productId);
        
        // ✅ TAMBAHKAN VALIDASI JIKA PRODUCT TIDAK DITEMUKAN
        if (!$product) {
            $this->progress = 0;
            $this->currentOrders = 0;
            $this->remainingSlots = 10; // Default quota
            return;
        }
        
        // ✅ UPDATE PROGRESS DARI PRODUCT YANG DITEMUKAN
        $this->progress = $product->progress_percentage ?? 0;
        $this->currentOrders = $product->paid_orders_count ?? 0;
        $this->remainingSlots = max(0, ($product->min_quota ?? 10) - $this->currentOrders);
        $this->productName = $product->name ?? 'Kaligrafi Lampu';
    }
    
    public function render()
    {
        $product = Product::find($this->productId);
        
        return view('livewire.po-progress', [
            'product' => $product,
            'batchNumber' => $product->current_batch ?? 1
        ]);
    }
}