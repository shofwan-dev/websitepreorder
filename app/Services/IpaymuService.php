<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class IPaymuService
{
    protected $va;
    protected $apiKey;
    protected $baseUrl;

    public function __construct()
    {
        $this->va = config('services.ipaymu.va');
        $this->apiKey = config('services.ipaymu.api_key');
        
        $environment = config('services.ipaymu.environment', 'sandbox');
        $this->baseUrl = $environment === 'production' 
            ? config('services.ipaymu.production_url')
            : config('services.ipaymu.sandbox_url');
    }

    /**
     * Generate signature for iPaymu API
     * Based on: https://storage.googleapis.com/ipaymu-docs/ipaymu-api/iPaymu-signature-documentation-v2.pdf
     */
    public function generateSignature($body = [], $method = 'POST')
    {
        // Encode body to JSON string
        $jsonBody = json_encode($body, JSON_UNESCAPED_SLASHES);
        
        // Create string to hash
        $requestBody = strtolower(hash('sha256', $jsonBody));
        $stringToSign = $method . ':' . $this->va . ':' . $requestBody . ':' . $this->apiKey;
        
        // Generate HMAC SHA256 signature
        $signature = hash_hmac('sha256', $stringToSign, $this->apiKey);
        
        return $signature;
    }

    /**
     * Get current timestamp in iPaymu format
     */
    public function getTimestamp()
    {
        return date('YmdHis');
    }

    /**
     * Check transaction status
     */
    public function checkTransaction($transactionId)
    {
        try {
            $timestamp = $this->getTimestamp();
            $body = [
                'transactionId' => $transactionId,
                'account' => $this->va
            ];
            
            $signature = $this->generateSignature($body, 'POST');
            
            Log::info('iPaymu Check Transaction', [
                'transactionId' => $transactionId,
                'timestamp' => $timestamp,
                'signature' => $signature
            ]);
            
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'signature' => $signature,
                'va' => $this->va,
                'timestamp' => $timestamp,
            ])->post($this->baseUrl . '/transaction', $body);
            
            $result = $response->json();
            
            Log::info('iPaymu Response', [
                'status' => $response->status(),
                'body' => $result
            ]);
            
            return [
                'success' => $response->successful(),
                'data' => $result,
                'status_code' => $response->status()
            ];
        } catch (\Exception $e) {
            Log::error('iPaymu Error: ' . $e->getMessage());
            
            return [
                'success' => false,
                'message' => $e->getMessage(),
                'data' => null
            ];
        }
    }

    /**
     * Create payment request
     */
    public function createPayment($orderData)
    {
        try {
            $timestamp = $this->getTimestamp();
            $body = [
                'product' => $orderData['product'] ?? [],
                'qty' => $orderData['qty'] ?? [],
                'price' => $orderData['price'] ?? [],
                'returnUrl' => $orderData['returnUrl'] ?? url('/'),
                'cancelUrl' => $orderData['cancelUrl'] ?? url('/'),
                'notifyUrl' => $orderData['notifyUrl'] ?? url('/ipaymu/callback'),
                'referenceId' => $orderData['referenceId'] ?? '',
                'buyerName' => $orderData['buyerName'] ?? '',
                'buyerEmail' => $orderData['buyerEmail'] ?? '',
                'buyerPhone' => $orderData['buyerPhone'] ?? '',
            ];
            
            $signature = $this->generateSignature($body, 'POST');
            
            Log::info('iPaymu Create Payment', [
                'body' => $body,
                'timestamp' => $timestamp
            ]);
            
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'signature' => $signature,
                'va' => $this->va,
                'timestamp' => $timestamp,
            ])->post($this->baseUrl . '/payment', $body);
            
            $result = $response->json();
            
            Log::info('iPaymu Payment Response', [
                'status' => $response->status(),
                'body' => $result
            ]);
            
            return [
                'success' => $response->successful(),
                'data' => $result,
                'status_code' => $response->status()
            ];
        } catch (\Exception $e) {
            Log::error('iPaymu Payment Error: ' . $e->getMessage());
            
            return [
                'success' => false,
                'message' => $e->getMessage(),
                'data' => null
            ];
        }
    }

    /**
     * Check if transaction is paid
     * Status: 1 (Berhasil), 6 (Refund), 7 (Expired)
     */
    public function isTransactionPaid($status)
    {
        return in_array($status, [1, 6, 7]);
    }

    /**
     * Test connection to iPaymu
     */
    public function testConnection()
    {
        try {
            // Try to check a dummy transaction to test connection
            $timestamp = $this->getTimestamp();
            $body = [
                'transactionId' => '1',
                'account' => $this->va
            ];
            
            $signature = $this->generateSignature($body, 'POST');
            
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'signature' => $signature,
                'va' => $this->va,
                'timestamp' => $timestamp,
            ])->post($this->baseUrl . '/transaction', $body);
            
            // If we get a response (even if error), connection is working
            return [
                'success' => true,
                'message' => 'Connection successful',
                'status_code' => $response->status()
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Connection failed: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Check Balance
     * Endpoint: POST /api/v2/balance
     */
    public function checkBalance()
    {
        try {
            $timestamp = $this->getTimestamp();
            $body = [
                'account' => $this->va
            ];
            
            $signature = $this->generateSignature($body, 'POST');
            
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'signature' => $signature,
                'va' => $this->va,
                'timestamp' => $timestamp,
            ])->post($this->baseUrl . '/balance', $body);
            
            $result = $response->json();
            
            Log::info('iPaymu Check Balance Response', [
                'status' => $response->status(),
                'body' => $result
            ]);
            
            return [
                'success' => $response->successful(),
                'data' => $result,
                'status_code' => $response->status()
            ];
        } catch (\Exception $e) {
            Log::error('iPaymu Balance Error: ' . $e->getMessage());
            
            return [
                'success' => false,
                'message' => $e->getMessage(),
                'data' => null
            ];
        }
    }

    /**
     * Get History Transaction
     * Endpoint: POST /api/v2/history
     */
    public function getHistoryTransaction($params = [])
    {
        try {
            $timestamp = $this->getTimestamp();
            $body = array_merge([
                'account' => $this->va,
                'startdate' => $params['startdate'] ?? date('Y-m-d', strtotime('-30 days')),
                'enddate' => $params['enddate'] ?? date('Y-m-d'),
                'page' => $params['page'] ?? 1,
                'limit' => $params['limit'] ?? 20,
            ], $params);
            
            $signature = $this->generateSignature($body, 'POST');
            
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'signature' => $signature,
                'va' => $this->va,
                'timestamp' => $timestamp,
            ])->post($this->baseUrl . '/history', $body);
            
            $result = $response->json();
            
            Log::info('iPaymu History Response', [
                'status' => $response->status(),
                'body' => $result
            ]);
            
            return [
                'success' => $response->successful(),
                'data' => $result,
                'status_code' => $response->status()
            ];
        } catch (\Exception $e) {
            Log::error('iPaymu History Error: ' . $e->getMessage());
            
            return [
                'success' => false,
                'message' => $e->getMessage(),
                'data' => null
            ];
        }
    }

    /**
     * Calculate COD Shipping
     * Endpoint: POST /api/v2/cod/shipping-calculate
     */
    public function calculateShipping($data)
    {
        try {
            $timestamp = $this->getTimestamp();
            $body = [
                'destination_area_id' => $data['destination_area_id'],
                'pickup_area_id' => $data['pickup_area_id'],
                'weight' => $data['weight'], // in kilogram
                'amount' => $data['amount'],
            ];
            
            $signature = $this->generateSignature($body, 'POST');
            
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'signature' => $signature,
                'va' => $this->va,
                'timestamp' => $timestamp,
            ])->asForm()->post($this->baseUrl . '/cod/shipping-calculate', $body);
            
            $result = $response->json();
            
            Log::info('iPaymu Shipping Calculate Response', [
                'status' => $response->status(),
                'body' => $result
            ]);
            
            return [
                'success' => $response->successful(),
                'data' => $result,
                'status_code' => $response->status()
            ];
        } catch (\Exception $e) {
            Log::error('iPaymu Shipping Calculate Error: ' . $e->getMessage());
            
            return [
                'success' => false,
                'message' => $e->getMessage(),
                'data' => null
            ];
        }
    }

    /**
     * Track COD Package
     * Endpoint: POST /api/v2/cod/tracking
     */
    public function trackPackage($awb = null, $transactionId = null)
    {
        try {
            if (!$awb && !$transactionId) {
                return [
                    'success' => false,
                    'message' => 'AWB or Transaction ID required',
                    'data' => null
                ];
            }

            $timestamp = $this->getTimestamp();
            $body = [];
            
            if ($awb) {
                $body['awb'] = $awb;
            }
            
            if ($transactionId) {
                $body['transaction_id'] = $transactionId;
            }
            
            $signature = $this->generateSignature($body, 'POST');
            
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'signature' => $signature,
                'va' => $this->va,
                'timestamp' => $timestamp,
            ])->asForm()->post($this->baseUrl . '/cod/tracking', $body);
            
            $result = $response->json();
            
            Log::info('iPaymu Tracking Response', [
                'status' => $response->status(),
                'body' => $result
            ]);
            
            return [
                'success' => $response->successful(),
                'data' => $result,
                'status_code' => $response->status()
            ];
        } catch (\Exception $e) {
            Log::error('iPaymu Tracking Error: ' . $e->getMessage());
            
            return [
                'success' => false,
                'message' => $e->getMessage(),
                'data' => null
            ];
        }
    }
}