<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class OrderTestController extends Controller
{
    /**
     * Show form to manually update order payment status
     */
    public function showUpdateForm($orderId)
    {
        $order = Order::with('product')->findOrFail($orderId);
        
        return view('admin.test-update-payment', compact('order'));
    }
    
    /**
     * Manually update order payment status (for testing)
     */
    public function updatePaymentStatus(Request $request, $orderId)
    {
        $order = Order::findOrFail($orderId);
        
        $validated = $request->validate([
            'payment_status' => 'required|in:pending,paid,partial,refunded,expired,failed',
        ]);
        
        $oldStatus = $order->payment_status;
        $order->payment_status = $validated['payment_status'];
        
        if ($validated['payment_status'] === 'paid' && !$order->paid_at) {
            $order->paid_at = now();
        }
        
        $order->save();
        
        Log::info('Manual payment status update', [
            'order_id' => $order->id,
            'old_status' => $oldStatus,
            'new_status' => $order->payment_status,
            'paid_at' => $order->paid_at,
        ]);
        
        return redirect()->route('user.orders.show', $order->id)
            ->with('success', "Status pembayaran berhasil diubah menjadi '{$order->payment_status}'");
    }
    
    /**
     * Quick update to paid
     */
    public function markAsPaid($orderId)
    {
        $order = Order::findOrFail($orderId);
        
        $order->payment_status = 'paid';
        $order->paid_at = now();
        $order->save();
        
        Log::info('Order marked as paid manually', [
            'order_id' => $order->id,
            'paid_at' => $order->paid_at,
        ]);
        
        return redirect()->route('user.orders.show', $order->id)
            ->with('success', 'Order berhasil ditandai sebagai LUNAS!');
    }
}
