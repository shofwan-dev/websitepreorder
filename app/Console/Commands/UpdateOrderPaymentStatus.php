<?php

namespace App\Console\Commands;

use App\Models\Order;
use Illuminate\Console\Command;

class UpdateOrderPaymentStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'order:update-payment {order_id} {status=paid}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update order payment status manually (for testing)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $orderId = $this->argument('order_id');
        $status = $this->argument('status');
        
        $order = Order::find($orderId);
        
        if (!$order) {
            $this->error("Order #{$orderId} not found!");
            return 1;
        }
        
        $this->info("Current order status:");
        $this->table(
            ['Field', 'Value'],
            [
                ['Order ID', $order->id],
                ['Customer', $order->customer_name],
                ['Payment Status', $order->payment_status],
                ['Paid At', $order->paid_at ?? 'NULL'],
                ['Transaction ID', $order->ipaymu_transaction_id ?? 'NULL'],
                ['Session ID', $order->ipaymu_session_id ?? 'NULL'],
            ]
        );
        
        if (!$this->confirm("Update payment status to '{$status}'?")) {
            $this->info('Cancelled.');
            return 0;
        }
        
        $order->payment_status = $status;
        
        if ($status === 'paid' && !$order->paid_at) {
            $order->paid_at = now();
        }
        
        $order->save();
        
        $this->info("✅ Order #{$orderId} payment status updated to '{$status}'");
        
        if ($status === 'paid') {
            $this->info("✅ Paid at: {$order->paid_at}");
        }
        
        return 0;
    }
}
