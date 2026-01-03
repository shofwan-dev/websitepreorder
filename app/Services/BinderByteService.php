<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class BinderByteService
{
    protected $apiKey;
    protected $baseUrl = 'https://api.binderbyte.com';

    public function __construct()
    {
        $this->apiKey = config('services.binderbyte.api_key') ?: env('BINDERBYTE_API_KEY');
    }

    /**
     * Get list of provinces
     */
    public function getProvinces()
    {
        return Cache::remember('binderbyte_provinces', 86400, function () {
            try {
                $response = Http::get("{$this->baseUrl}/wilayah/provinsi", [
                    'api_key' => $this->apiKey
                ]);

                if ($response->successful()) {
                    $data = $response->json();
                    return [
                        'success' => true,
                        'data' => $data['value'] ?? []
                    ];
                }

                return ['success' => false, 'message' => 'Failed to fetch provinces'];
            } catch (\Exception $e) {
                Log::error('BinderByte getProvinces error: ' . $e->getMessage());
                return ['success' => false, 'message' => $e->getMessage()];
            }
        });
    }

    /**
     * Get cities by province ID
     */
    public function getCities($provinceId)
    {
        return Cache::remember("binderbyte_cities_{$provinceId}", 86400, function () use ($provinceId) {
            try {
                $response = Http::get("{$this->baseUrl}/wilayah/kabupaten", [
                    'api_key' => $this->apiKey,
                    'id_provinsi' => $provinceId
                ]);

                if ($response->successful()) {
                    $data = $response->json();
                    return [
                        'success' => true,
                        'data' => $data['value'] ?? []
                    ];
                }

                return ['success' => false, 'message' => 'Failed to fetch cities'];
            } catch (\Exception $e) {
                Log::error('BinderByte getCities error: ' . $e->getMessage());
                return ['success' => false, 'message' => $e->getMessage()];
            }
        });
    }

    /**
     * Get districts by city ID
     */
    public function getDistricts($cityId)
    {
        return Cache::remember("binderbyte_districts_{$cityId}", 86400, function () use ($cityId) {
            try {
                $response = Http::get("{$this->baseUrl}/wilayah/kecamatan", [
                    'api_key' => $this->apiKey,
                    'id_kabupaten' => $cityId
                ]);

                if ($response->successful()) {
                    $data = $response->json();
                    return [
                        'success' => true,
                        'data' => $data['value'] ?? []
                    ];
                }

                return ['success' => false, 'message' => 'Failed to fetch districts'];
            } catch (\Exception $e) {
                Log::error('BinderByte getDistricts error: ' . $e->getMessage());
                return ['success' => false, 'message' => $e->getMessage()];
            }
        });
    }

    /**
     * Track package
     */
    public function trackPackage($courier, $awb)
    {
        try {
            $response = Http::get("{$this->baseUrl}/v1/track", [
                'api_key' => $this->apiKey,
                'courier' => $courier,
                'awb' => $awb
            ]);

            if ($response->successful()) {
                $data = $response->json();
                
                if ($data['status'] == 200) {
                    return [
                        'success' => true,
                        'data' => $data['data']
                    ];
                }
            }

            return ['success' => false, 'message' => 'Failed to track package'];
        } catch (\Exception $e) {
            Log::error('BinderByte trackPackage error: ' . $e->getMessage());
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * Get list of supported couriers
     */
    public function getCouriers()
    {
        return Cache::remember('binderbyte_couriers', 86400, function () {
            try {
                $response = Http::get("{$this->baseUrl}/v1/list_courier", [
                    'api_key' => $this->apiKey
                ]);

                if ($response->successful()) {
                    return [
                        'success' => true,
                        'data' => $response->json()
                    ];
                }

                return ['success' => false, 'message' => 'Failed to fetch couriers'];
            } catch (\Exception $e) {
                Log::error('BinderByte getCouriers error: ' . $e->getMessage());
                return ['success' => false, 'message' => $e->getMessage()];
            }
        });
    }

    /**
     * Check API quota
     */
    public function checkQuota()
    {
        try {
            $response = Http::get("{$this->baseUrl}/v1/checkQuota", [
                'api_key' => $this->apiKey
            ]);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json()
                ];
            }

            return ['success' => false, 'message' => 'Failed to check quota'];
        } catch (\Exception $e) {
            Log::error('BinderByte checkQuota error: ' . $e->getMessage());
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
}
