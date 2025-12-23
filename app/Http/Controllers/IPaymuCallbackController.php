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
            // Get transaction ID from callback (can be trx_id, transactionId, or sid for SessionID)
            $transactionId = $request->input('trx_id') 
                          ?? $request->input('transactionId')
                          ?? $request->input('sid')
                          ?? $request->input('session_id');
            
            $referenceId = $request->input('reference_id') ?? $request->input('referenceId');
            
            if (!$transactionId && !$referenceId) {
                Log::error('iPaymu Callback: No transaction ID or reference ID');
                return response()->json(['status' => 'error', 'message' => 'No transaction ID or reference ID'], 400);
            }

            // Try to find order by transaction ID first
            $order = null;
            
            if ($transactionId) {
                $order = Order::where('ipaymu_transaction_id', $transactionId)->first();
                
                // If not found by transaction_id, try session_id
                if (!$order) {
                    $order = Order::where('ipaymu_session_id', $transactionId)->first();
                }
            }
            
            // If still not found and we have referenceId, try that
            if (!$order && $referenceId) {
                // Reference ID format is "ORDER-{id}"
                $orderId = str_replace('ORDER-', '', $referenceId);
                if (is_numeric($orderId)) {
                    $order = Order::find($orderId);
                }
            }

            if (!$order) {
                Log::error('iPaymu Callback: Order not found', [
                    'transactionId' => $transactionId,
                    'referenceId' => $referenceId
                ]);
                return response()->json(['status' => 'error', 'message' => 'Order not found'], 404);
            }

            // Get status from callback data
            $status = $request->input('status') ?? $request->input('Status');
            
            // If status not in callback, check transaction via API
            if (!$status && $transactionId) {
                $result = $this->ipaymu->checkTransaction($transactionId);
                
                if ($result['success']) {
                    $status = $result['data']['Data']['Status'] ?? null;
                }
            }

            Log::info('iPaymu Transaction Status', [
                'order_id' => $order->id,
                'transactionId' => $transactionId,
                'referenceId' => $referenceId,
                'status' => $status
            ]);

            // Update order based on status
            // Status: 1 (Berhasil), 6 (Refund), 7 (Expired)
            if ($status) {
                $statusInt = (int)$status;
                
                if ($statusInt == 1) {
                    $order->payment_status = 'paid';
                    $order->paid_at = now();
                    Log::info('Order paid successfully', ['order_id' => $order->id]);
                    
                    // Send WhatsApp notification - Payment Success
                    $this->sendPaymentNotification($order, 'success');
                    
                } elseif ($statusInt == 6) {
                    $order->payment_status = 'refunded';
                    Log::info('Order refunded', ['order_id' => $order->id]);
                    
                    // Send WhatsApp notification - Payment Refunded
                    $this->sendPaymentNotification($order, 'refunded');
                    
                } elseif ($statusInt == 7) {
                    $order->payment_status = 'expired';
                    Log::info('Order expired', ['order_id' => $order->id]);
                    
                    // Send WhatsApp notification - Payment Expired
                    $this->sendPaymentNotification($order, 'expired');
                    
                } elseif ($statusInt == -2) {
                    $order->payment_status = 'expired';
                    Log::info('Order expired (status -2)', ['order_id' => $order->id]);
                    
                    // Send WhatsApp notification - Payment Expired
                    $this->sendPaymentNotification($order, 'expired');
                    
                } elseif ($statusInt == 0) {
                    $order->payment_status = 'pending';
                    Log::info('Order still pending', ['order_id' => $order->id]);
                }
                
                $order->save();
                
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
     * Send WhatsApp notification based on payment status
     */
    private function sendPaymentNotification($order, $type)
    {
        try {
            $whatsapp = app(\App\Services\WhatsAppService::class);
            
            switch ($type) {
                case 'success':
                    $whatsapp->sendPaymentSuccessNotification($order);
                    break;
                case 'refunded':
                    $whatsapp->sendPaymentRefundedNotification($order);
                    break;
                case 'expired':
                    $whatsapp->sendPaymentExpiredNotification($order);
                    break;
                case 'failed':
                    $whatsapp->sendPaymentFailedNotification($order);
                    break;
            }
            
            Log::info('WhatsApp payment notification sent', [
                'order_id' => $order->id,
                'type' => $type
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send WhatsApp payment notification', [
                'order_id' => $order->id,
                'type' => $type,
                'error' => $e->getMessage()
            ]);
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
