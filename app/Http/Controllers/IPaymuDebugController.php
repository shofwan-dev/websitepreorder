<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class IPaymuDebugController extends Controller
{
    /**
     * Debug callback - shows what data is received
     */
    public function debugCallback(Request $request)
    {
        $data = $request->all();
        
        Log::info('=== IPAYMU DEBUG CALLBACK ===', [
            'all_data' => $data,
            'headers' => $request->headers->all(),
            'method' => $request->method(),
        ]);
        
        // Extract possible IDs
        $transactionId = $request->input('trx_id') 
                      ?? $request->input('transactionId')
                      ?? $request->input('sid')
                      ?? $request->input('session_id');
        
        $referenceId = $request->input('reference_id') ?? $request->input('referenceId');
        $status = $request->input('status') ?? $request->input('Status');
        
        // Try to find order
        $order = null;
        $searchMethod = null;
        
        if ($transactionId) {
            $order = Order::where('ipaymu_transaction_id', $transactionId)->first();
            if ($order) {
                $searchMethod = 'ipaymu_transaction_id';
            } else {
                $order = Order::where('ipaymu_session_id', $transactionId)->first();
                if ($order) {
                    $searchMethod = 'ipaymu_session_id';
                }
            }
        }
        
        if (!$order && $referenceId) {
            $orderId = str_replace('ORDER-', '', $referenceId);
            if (is_numeric($orderId)) {
                $order = Order::find($orderId);
                if ($order) {
                    $searchMethod = 'reference_id';
                }
            }
        }
        
        $debugInfo = [
            'callback_received' => true,
            'timestamp' => now()->toDateTimeString(),
            'extracted_data' => [
                'transaction_id' => $transactionId,
                'reference_id' => $referenceId,
                'status' => $status,
                'status_type' => gettype($status),
            ],
            'order_search' => [
                'found' => $order ? true : false,
                'search_method' => $searchMethod,
                'order_id' => $order ? $order->id : null,
                'current_payment_status' => $order ? $order->payment_status : null,
                'ipaymu_transaction_id' => $order ? $order->ipaymu_transaction_id : null,
                'ipaymu_session_id' => $order ? $order->ipaymu_session_id : null,
            ],
            'status_interpretation' => $this->interpretStatus($status),
        ];
        
        Log::info('=== DEBUG INFO ===', $debugInfo);
        
        return response()->json([
            'debug' => $debugInfo,
            'raw_request' => $data,
        ], 200);
    }
    
    /**
     * Interpret status value
     */
    private function interpretStatus($status)
    {
        if (!$status) {
            return 'NO STATUS PROVIDED';
        }
        
        $statusLower = is_string($status) ? strtolower($status) : '';
        $statusInt = is_numeric($status) ? (int)$status : null;
        
        $interpretation = [
            'original_value' => $status,
            'type' => gettype($status),
            'as_string_lower' => $statusLower,
            'as_integer' => $statusInt,
            'will_be_marked_as' => 'UNKNOWN',
        ];
        
        if ($statusLower === 'berhasil' || $statusInt === 1) {
            $interpretation['will_be_marked_as'] = 'PAID';
        } elseif ($statusLower === 'refund' || $statusInt === 6) {
            $interpretation['will_be_marked_as'] = 'REFUNDED';
        } elseif ($statusLower === 'expired' || $statusInt === 7 || $statusInt === -2) {
            $interpretation['will_be_marked_as'] = 'EXPIRED';
        } elseif ($statusLower === 'pending' || $statusInt === 0) {
            $interpretation['will_be_marked_as'] = 'PENDING';
        } elseif ($statusLower === 'gagal' || $statusLower === 'failed' || $statusInt === 5) {
            $interpretation['will_be_marked_as'] = 'FAILED';
        }
        
        return $interpretation;
    }
    
    /**
     * List recent orders with iPaymu data
     */
    public function listOrders()
    {
        $orders = Order::with('product')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get()
            ->map(function($order) {
                return [
                    'id' => $order->id,
                    'customer_name' => $order->customer_name,
                    'total_amount' => $order->total_amount,
                    'payment_status' => $order->payment_status,
                    'paid_at' => $order->paid_at?->toDateTimeString(),
                    'ipaymu_transaction_id' => $order->ipaymu_transaction_id,
                    'ipaymu_session_id' => $order->ipaymu_session_id,
                    'ipaymu_payment_url' => $order->ipaymu_payment_url ? 'SET' : 'NULL',
                    'payment_expired_at' => $order->payment_expired_at?->toDateTimeString(),
                    'created_at' => $order->created_at->toDateTimeString(),
                ];
            });
        
        return response()->json([
            'orders' => $orders,
            'count' => $orders->count(),
        ]);
    }
}
