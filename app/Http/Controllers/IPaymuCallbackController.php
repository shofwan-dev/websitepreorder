<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\IPaymuService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class IPaymuCallbackController extends Controller
{
    protected $ipaymu;

    public function __construct(IPaymuService $ipaymu)
    {
        $this->ipaymu = $ipaymu;
    }

    /**
     * Handle iPaymu callback/notification
     */
    public function callback(Request $request)
    {
        Log::info('iPaymu Callback Received', $request->all());

        try {
            // Get transaction ID from callback
            $transactionId = $request->input('trx_id') ?? $request->input('transactionId');
            
            if (!$transactionId) {
                Log::error('iPaymu Callback: No transaction ID');
                return response()->json(['status' => 'error', 'message' => 'No transaction ID'], 400);
            }

            // Check transaction status from iPaymu
            $result = $this->ipaymu->checkTransaction($transactionId);

            if (!$result['success']) {
                Log::error('iPaymu Callback: Failed to check transaction', $result);
                return response()->json(['status' => 'error', 'message' => 'Failed to check transaction'], 500);
            }

            $data = $result['data'];
            $status = $data['Data']['Status'] ?? null;

            Log::info('iPaymu Transaction Status', [
                'transactionId' => $transactionId,
                'status' => $status,
                'data' => $data
            ]);

            // Find order by transaction ID
            $order = Order::where('ipaymu_transaction_id', $transactionId)->first();

            if (!$order) {
                Log::error('iPaymu Callback: Order not found', ['transactionId' => $transactionId]);
                return response()->json(['status' => 'error', 'message' => 'Order not found'], 404);
            }

            // Update order based on status
            // Status: 1 (Berhasil), 6 (Refund), 7 (Expired)
            if ($this->ipaymu->isTransactionPaid($status)) {
                if ($status == 1) {
                    $order->payment_status = 'paid';
                    $order->paid_at = now();
                    Log::info('Order paid successfully', ['order_id' => $order->id]);
                } elseif ($status == 6) {
                    $order->payment_status = 'refunded';
                    Log::info('Order refunded', ['order_id' => $order->id]);
                } elseif ($status == 7) {
                    $order->payment_status = 'expired';
                    Log::info('Order expired', ['order_id' => $order->id]);
                }
                
                $order->save();
                
                // TODO: Send notification to customer
                // TODO: Update product quota
            }

            return response()->json(['status' => 'success', 'message' => 'Callback processed']);

        } catch (\Exception $e) {
            Log::error('iPaymu Callback Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Handle return URL from iPaymu after payment
     */
    public function return(Request $request)
    {
        $transactionId = $request->input('trx_id') ?? $request->input('transactionId');
        
        Log::info('iPaymu Return URL', [
            'transactionId' => $transactionId,
            'all_params' => $request->all()
        ]);

        if ($transactionId) {
            $order = Order::where('ipaymu_transaction_id', $transactionId)->first();
            
            if ($order) {
                // Redirect to order detail with success message
                return redirect()->route('user.orders.show', $order->id)
                    ->with('success', 'Pembayaran sedang diproses. Kami akan mengirim notifikasi jika pembayaran berhasil.');
            }
        }

        // Fallback to orders list
        return redirect()->route('user.orders.index')
            ->with('info', 'Terima kasih. Status pembayaran akan diupdate segera.');
    }

    /**
     * Handle cancel URL from iPaymu
     */
    public function cancel(Request $request)
    {
        Log::info('iPaymu Cancel URL', $request->all());

        return redirect()->route('user.orders.index')
            ->with('warning', 'Pembayaran dibatalkan. Anda dapat melanjutkan pembayaran kapan saja.');
    }
}
