<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Order;
use App\Models\Product;
use App\Events\ProductionStageUpdated;

class ProductionManager extends Component
{
    public $productId;
    public $currentStage = 'po_open';
    public $shippingNumber = '';
    
    // Tahapan produksi
    public $stages = [
        'po_open' => 'PO Dibuka',
        'waiting_quota' => 'Menunggu Kuota',
        'production' => 'Produksi',
        'qc' => 'Quality Control',
        'packaging' => 'Pengemasan',
        'shipping' => 'Pengiriman',
        'delivered' => 'Terkirim'
    ];
    
    public function updateStage($stage)
    {
        $product = Product::find($this->productId);
        
        // Update tahap produksi utama
        $product->productionTimeline()->update([
            'stage' => $stage,
            'updated_at' => now()
        ]);
        
        // Dapatkan semua order yang sudah bayar
        $orders = Order::where('product_id', $this->productId)
            ->where('payment_status', 'paid')
            ->where('is_verified', true)
            ->get();
        
        // Trigger event untuk setiap pelanggan
        foreach ($orders as $order) {
            $additionalInfo = '';
            
            if ($stage === 'shipping') {
                $additionalInfo = $this->shippingNumber;
            }
            
            event(new ProductionStageUpdated($order, $stage, $additionalInfo));
        }
        
        // Update UI
        $this->currentStage = $stage;
        $this->dispatch('stage-updated');
        
        session()->flash('message', 'Tahap produksi diperbarui dan notifikasi dikirim!');
    }
    
    public function render()
    {
        return view('livewire.admin.production-manager', [
            'product' => Product::find($this->productId),
            'orders' => Order::where('product_id', $this->productId)
                ->where('payment_status', 'paid')
                ->get()
        ]);
    }
}