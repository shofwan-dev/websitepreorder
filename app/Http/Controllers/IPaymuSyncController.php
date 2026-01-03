<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\IPaymuService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class IPaymuSyncController extends Controller
{
    protected $ipaymu;

    public function __construct(IPaymuService $ipaymu)
    {
        $this->ipaymu = $ipaymu;
    }

    /**
     * Check and sync order status with iPaymu
     */
    public function syncOrderStatus($orderId)
    {
        $order = Order::findOrFail($orderId);
        
        // Check if order has iPaymu transaction
        if (!$order->ipaymu_transaction_id && !$order->ipaymu_session_id) {
            return response()->json([
                'success' => false,
                'message' => 'Order tidak memiliki transaction ID dari iPaymu',
                'order' => [
                    'id' => $order->id,
                    'payment_status' => $order->payment_status,
                    'ipaymu_transaction_id' => null,
                    'ipaymu_session_id' => null,
                ]
            ], 400);
        }
        
        // Use transaction_id or session_id
        $transactionId = $order->ipaymu_transaction_id ?? $order->ipaymu_session_id;
        
        Log::info('Syncing order with iPaymu', [
            'order_id' => $order->id,
            'transaction_id' => $transactionId,
        ]);
        
        // Check transaction status from iPaymu
        $result = $this->ipaymu->checkTransaction($transactionId);
        
        if (!$result['success']) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengecek status di iPaymu: ' . ($result['message'] ?? 'Unknown error'),
                'order' => [
                    'id' => $order->id,
                    'payment_status' => $order->payment_status,
                ],
                'ipaymu_response' => $result,
            ], 500);
        }
        
        // Get status from iPaymu response
        $ipaymuData = $result['data']['Data'] ?? [];
        $ipaymuStatus = $ipaymuData['Status'] ?? null;
        $statusCode = $ipaymuData['StatusCode'] ?? null;
        
        $oldStatus = $order->payment_status;
        $newStatus = $this->mapIPaymuStatus($ipaymuStatus, $statusCode);
        
        // Update order status based on iPaymu
        if ($newStatus) {
            $order->payment_status = $newStatus;
            
            if ($newStatus === 'paid' && !$order->paid_at) {
                $order->paid_at = now();
            }
            
            // Update transaction ID if we got it
            if (!empty($ipaymuData['TransactionId']) && !$order->ipaymu_transaction_id) {
                $order->ipaymu_transaction_id = $ipaymuData['TransactionId'];
            }
            
            $order->save();
            
            Log::info('Order status synced with iPaymu', [
                'order_id' => $order->id,
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
                'ipaymu_status' => $ipaymuStatus,
                'ipaymu_status_code' => $statusCode,
            ]);
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Status berhasil disinkronkan dengan iPaymu',
            'order' => [
                'id' => $order->id,
                'old_payment_status' => $oldStatus,
                'new_payment_status' => $order->payment_status,
                'paid_at' => $order->paid_at,
                'status_changed' => $oldStatus !== $order->payment_status,
            ],
            'ipaymu_data' => [
                'transaction_id' => $ipaymuData['TransactionId'] ?? null,
                'status' => $ipaymuStatus,
                'status_code' => $statusCode,
                'amount' => $ipaymuData['Total'] ?? null,
                'payment_method' => $ipaymuData['Via'] ?? null,
                'payment_channel' => $ipaymuData['Channel'] ?? null,
            ],
            'raw_response' => $result,
        ]);
    }
    
    /**
     * Map iPaymu status to our payment status
     */
    private function mapIPaymuStatus($status, $statusCode = null)
    {
        // Handle string status
        if (is_string($status)) {
            $statusLower = strtolower($status);
            
            if ($statusLower === 'berhasil' || $statusLower === 'success') {
                return 'paid';
            } elseif ($statusLower === 'pending') {
                return 'pending';
            } elseif ($statusLower === 'expired') {
                return 'expired';
            } elseif ($statusLower === 'gagal' || $statusLower === 'failed') {
                return 'failed';
            } elseif ($statusLower === 'refund') {
                return 'refunded';
            }
        }
        
        // Handle integer status code
        if (is_numeric($statusCode)) {
            $code = (int)$statusCode;
            
            switch ($code) {
                case 1:
                    return 'paid';
                case 0:
                    return 'pending';
                case -2:
                case 7:
                    return 'expired';
                case 5:
                    return 'failed';
                case 6:
                    return 'refunded';
            }
        }
        
        // Handle integer status
        if (is_numeric($status)) {
            $statusInt = (int)$status;
            
            switch ($statusInt) {
                case 1:
                    return 'paid';
                case 0:
                    return 'pending';
                case -2:
                case 7:
                    return 'expired';
                case 5:
                    return 'failed';
                case 6:
                    return 'refunded';
            }
        }
        
        return null;
    }
    
    /**
     * Sync all pending orders with iPaymu
     */
    public function syncAllPendingOrders()
    {
        $pendingOrders = Order::where('payment_status', 'pending')
            ->whereNotNull('ipaymu_transaction_id')
            ->orWhereNotNull('ipaymu_session_id')
            ->get();
        
        $results = [];
        
        foreach ($pendingOrders as $order) {
            $transactionId = $order->ipaymu_transaction_id ?? $order->ipaymu_session_id;
            
            if (!$transactionId) {
                continue;
            }
            
            $result = $this->ipaymu->checkTransaction($transactionId);
            
            if ($result['success']) {
                $ipaymuData = $result['data']['Data'] ?? [];
                $ipaymuStatus = $ipaymuData['Status'] ?? null;
                $statusCode = $ipaymuData['StatusCode'] ?? null;
                
                $newStatus = $this->mapIPaymuStatus($ipaymuStatus, $statusCode);
                
                if ($newStatus && $newStatus !== $order->payment_status) {
                    $oldStatus = $order->payment_status;
                    $order->payment_status = $newStatus;
                    
                    if ($newStatus === 'paid' && !$order->paid_at) {
                        $order->paid_at = now();
                    }
                    
                    $order->save();
                    
                    $results[] = [
                        'order_id' => $order->id,
                        'old_status' => $oldStatus,
                        'new_status' => $newStatus,
                        'synced' => true,
                    ];
                } else {
                    $results[] = [
                        'order_id' => $order->id,
                        'status' => $order->payment_status,
                        'synced' => false,
                        'reason' => 'No change needed',
                    ];
                }
            } else {
                $results[] = [
                    'order_id' => $order->id,
                    'synced' => false,
                    'reason' => 'Failed to check iPaymu: ' . ($result['message'] ?? 'Unknown'),
                ];
            }
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Sync completed',
            'total_orders' => count($results),
            'results' => $results,
        ]);
    }
}
