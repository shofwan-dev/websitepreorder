<?php

namespace App\Jobs;

use App\Models\Order;
use App\Services\IpaymuService;
use App\Events\OrderPaid;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class VerifyIpaymuPayment implements ShouldQueue
{
    use Queueable;
    
    protected $order;
    
    public function __construct(Order $order)
    {
        $this->order = $order;
    }
    
    public function handle(IpaymuService $ipaymu)
    {
        $result = $ipaymu->checkTransaction($this->order->transaction_id);
        
        if ($result['success']) {
            $this->order->update([
                'payment_status' => 'paid',
                'ipaymu_status' => $result['status'],
                'is_verified' => true,
                'verified_at' => now()
            ]);
            
            // Trigger Livewire updates
            event(new \App\Events\OrderPaid($this->order->product_id));
        }
    }
}