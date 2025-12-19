<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of orders
     */
    public function index(Request $request)
    {
        $query = Order::with(['user', 'product', 'batch']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by payment status
        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        // Search by customer name or order ID
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('id', 'like', '%' . $request->search . '%')
                  ->orWhere('customer_name', 'like', '%' . $request->search . '%')
                  ->orWhere('customer_phone', 'like', '%' . $request->search . '%');
            });
        }

        $orders = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Display pending orders
     */
    public function pending()
    {
        $orders = Order::with(['user', 'product'])
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.orders.pending', compact('orders'));
    }

    /**
     * Display the specified order
     */
    public function show(Order $order)
    {
        $order->load(['user', 'product', 'batch']);

        return view('admin.orders.show', compact('order'));
    }

    /**
     * Update order status
     */
    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => ['required', 'in:pending,confirmed,processing,production,shipping,completed,cancelled'],
        ]);

        $oldStatus = $order->status;
        $order->status = $validated['status'];
        $order->save();

        // Send notification if status changed
        if ($oldStatus !== $validated['status']) {
            // You can trigger WhatsApp notification here if needed
        }

        return back()->with('success', 'Status order berhasil diperbarui.');
    }

    /**
     * Update payment status
     */
    public function updatePaymentStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'payment_status' => ['required', 'in:pending,paid,partial,refunded'],
        ]);

        $order->payment_status = $validated['payment_status'];
        $order->save();

        return back()->with('success', 'Status pembayaran berhasil diperbarui.');
    }

    /**
     * Send notification to customer
     */
    public function sendNotification(Request $request, Order $order)
    {
        $validated = $request->validate([
            'message' => ['required', 'string', 'max:1000'],
        ]);

        try {
            $whatsapp = new WhatsAppService();
            $result = $whatsapp->sendMessage(
                $order->customer_phone,
                $validated['message']
            );

            if ($result['success']) {
                return back()->with('success', 'Notifikasi berhasil dikirim.');
            } else {
                return back()->with('error', 'Gagal mengirim notifikasi: ' . ($result['message'] ?? 'Unknown error'));
            }
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengirim notifikasi: ' . $e->getMessage());
        }
    }
}
