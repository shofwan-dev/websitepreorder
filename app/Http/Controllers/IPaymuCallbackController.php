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
            
            // Get additional payment info
            $paymentMethod = $request->input('payment_method');
            $paymentChannel = $request->input('payment_channel');
            $realTrxId = $request->input('trx_id');
            
            // Update transaction ID if we got the real one from callback
            if ($realTrxId && !$order->ipaymu_transaction_id) {
                $order->ipaymu_transaction_id = $realTrxId;
            }
            
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
                'realTrxId' => $realTrxId,
                'referenceId' => $referenceId,
                'status' => $status,
                'status_type' => gettype($status),
                'payment_method' => $paymentMethod,
                'payment_channel' => $paymentChannel,
            ]);

            // Update order based on status
            // Status can be: 
            // String: "berhasil", "pending", "gagal", "expired"
            // Integer: 1 (Berhasil), 0 (Pending), -2 (Expired), 6 (Refund), 7 (Expired)
            if ($status) {
                $statusLower = is_string($status) ? strtolower($status) : '';
                $statusInt = is_numeric($status) ? (int)$status : null;
                
                // Check for success status
                if ($statusLower === 'berhasil' || $statusInt === 1) {
                    $order->payment_status = 'paid';
                    $order->paid_at = now();
                    Log::info('Order paid successfully', [
                        'order_id' => $order->id,
                        'status_received' => $status
                    ]);
                    
                    // Send WhatsApp notification - Payment Success
                    $this->sendPaymentNotification($order, 'success');
                    
                } elseif ($statusLower === 'refund' || $statusInt === 6) {
                    $order->payment_status = 'refunded';
                    Log::info('Order refunded', ['order_id' => $order->id]);
                    
                    // Send WhatsApp notification - Payment Refunded
                    $this->sendPaymentNotification($order, 'refunded');
                    
                } elseif ($statusLower === 'expired' || $statusInt === 7 || $statusInt === -2) {
                    $order->payment_status = 'expired';
                    Log::info('Order expired', [
                        'order_id' => $order->id,
                        'status_received' => $status
                    ]);
                    
                    // Send WhatsApp notification - Payment Expired
                    $this->sendPaymentNotification($order, 'expired');
                    
                } elseif ($statusLower === 'pending' || $statusInt === 0) {
                    $order->payment_status = 'pending';
                    Log::info('Order still pending', ['order_id' => $order->id]);
                    
                } elseif ($statusLower === 'gagal' || $statusLower === 'failed' || $statusInt === 5) {
                    $order->payment_status = 'failed';
                    Log::info('Order payment failed', ['order_id' => $order->id]);
                    
                    // Send WhatsApp notification - Payment Failed
                    $this->sendPaymentNotification($order, 'failed');
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
        $sessionId = $request->input('sid') ?? $request->input('session_id');
        $status = $request->input('status') ?? $request->input('Status');
        
        Log::info('iPaymu Return URL', [
            'transactionId' => $transactionId,
            'sessionId' => $sessionId,
            'status' => $status,
            'all_params' => $request->all()
        ]);

        // Try to find order
        $order = null;
        
        if ($transactionId) {
            $order = Order::where('ipaymu_transaction_id', $transactionId)->first();
        }
        
        // If not found by transaction ID, try session ID
        if (!$order && $sessionId) {
            $order = Order::where('ipaymu_session_id', $sessionId)->first();
        }
        
        if ($order) {
            // Update order with transaction ID if we got it
            if ($transactionId && $order->ipaymu_transaction_id !== $transactionId) {
                $order->ipaymu_transaction_id = $transactionId;
                $order->save();
            }
            
            // If status is provided in return URL (sandbox simulation), process it immediately
            if ($status) {
                $statusLower = is_string($status) ? strtolower($status) : '';
                
                if ($statusLower === 'berhasil' || $status == 1) {
                    $order->payment_status = 'paid';
                    $order->paid_at = now();
                    $order->save();
                    
                    Log::info('Payment marked as paid from return URL', [
                        'order_id' => $order->id,
                        'status' => $status
                    ]);
                    
                    // Send WhatsApp notification
                    try {
                        $whatsapp = app(\App\Services\WhatsAppService::class);
                        $whatsapp->sendPaymentSuccessNotification($order);
                    } catch (\Exception $e) {
                        Log::error('Failed to send WhatsApp', ['error' => $e->getMessage()]);
                    }
                    
                    return redirect()->route('user.orders.show', $order->id)
                        ->with('success', 'Pembayaran berhasil! Terima kasih atas kepercayaan Anda.');
                }
            }
            
            // Redirect to order detail with pending message
            return redirect()->route('user.orders.show', $order->id)
                ->with('success', 'Pembayaran sedang diproses. Kami akan mengirim notifikasi jika pembayaran berhasil.');
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
