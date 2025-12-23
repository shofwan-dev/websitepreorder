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

        // Send WhatsApp notification if status changed
        if ($oldStatus !== $validated['status']) {
            try {
                $statusMessages = [
                    'pending' => '⏳ Pesanan Anda sedang menunggu konfirmasi.',
                    'confirmed' => '✅ Pesanan Anda telah dikonfirmasi dan akan segera diproses.',
                    'processing' => '⚙️ Pesanan Anda sedang dalam proses persiapan.',
                    'production' => '🏭 Produk Anda sedang dalam tahap produksi.',
                    'shipping' => '🚚 Pesanan Anda sedang dalam pengiriman.',
                    'completed' => '🎉 Pesanan Anda telah selesai. Terima kasih!',
                    'cancelled' => '❌ Pesanan Anda telah dibatalkan.',
                ];

                $message = "*Update Status Pesanan #" . $order->id . "*\n\n";
                $message .= "Halo *" . $order->customer_name . "*,\n\n";
                $message .= $statusMessages[$validated['status']] ?? 'Status pesanan telah diperbarui.';
                $message .= "\n\n*Detail Pesanan:*\n";
                $message .= "Produk: " . $order->product->name . "\n";
                $message .= "Jumlah: " . $order->quantity . "\n";
                $message .= "Total: Rp " . number_format($order->total_amount, 0, ',', '.') . "\n\n";
                $message .= "Terima kasih telah berbelanja dengan kami! 🙏";

                $whatsapp = new WhatsAppService();
                $whatsapp->sendMessage($order->customer_phone, $message);
            } catch (\Exception $e) {
                // Log error but don't stop the process
                \Log::error('Failed to send WhatsApp notification: ' . $e->getMessage());
            }
        }

        return back()->with('success', 'Status order berhasil diperbarui dan notifikasi telah dikirim.');
    }

    /**
     * Update payment status
     */
    public function updatePaymentStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'payment_status' => ['required', 'in:pending,paid,partial,refunded'],
        ]);

        $oldPaymentStatus = $order->payment_status;
        $order->payment_status = $validated['payment_status'];
        $order->save();

        // Send WhatsApp notification if payment status changed
        if ($oldPaymentStatus !== $validated['payment_status']) {
            try {
                $paymentMessages = [
                    'pending' => '⏳ Menunggu pembayaran.',
                    'partial' => '💰 Pembayaran sebagian telah diterima.',
                    'paid' => '✅ Pembayaran telah lunas. Terima kasih!',
                    'failed' => '❌ Pembayaran gagal. Silakan coba lagi.',
                    'expired' => '⌛ Pembayaran telah kadaluarsa.',
                    'refunded' => '💸 Pembayaran telah dikembalikan (refund).',
                ];

                $message = "*Update Status Pembayaran #" . $order->id . "*\n\n";
                $message .= "Halo *" . $order->customer_name . "*,\n\n";
                $message .= $paymentMessages[$validated['payment_status']] ?? 'Status pembayaran telah diperbarui.';
                $message .= "\n\n*Detail Pesanan:*\n";
                $message .= "Produk: " . $order->product->name . "\n";
                $message .= "Total: Rp " . number_format($order->total_amount, 0, ',', '.') . "\n";
                $message .= "Status Pembayaran: " . strtoupper($validated['payment_status']) . "\n\n";
                $message .= "Terima kasih! 🙏";

                $whatsapp = new WhatsAppService();
                $whatsapp->sendMessage($order->customer_phone, $message);
            } catch (\Exception $e) {
                // Log error but don't stop the process
                \Log::error('Failed to send WhatsApp notification: ' . $e->getMessage());
            }
        }

        return back()->with('success', 'Status pembayaran berhasil diperbarui dan notifikasi telah dikirim.');
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
