<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class RajaOngkirService
{
    protected $apiKey;
    protected $baseUrl;
    protected $accountType; // starter, basic, pro

    public function __construct()
    {
        $this->apiKey = \App\Models\Setting::getValue('rajaongkir_api_key') ?: (config('services.rajaongkir.api_key') ?: env('RAJAONGKIR_API_KEY'));
        $this->accountType = \App\Models\Setting::getValue('rajaongkir_account_type') ?: config('services.rajaongkir.account_type', 'starter');
        $this->baseUrl = \App\Models\Setting::getValue('rajaongkir_base_url');
        
        if (!$this->baseUrl) {
            // Set default base URL based on account type
            if ($this->accountType === 'pro') {
                $this->baseUrl = 'https://pro.rajaongkir.com/api';
            } elseif ($this->accountType === 'basic') {
                $this->baseUrl = 'https://api.rajaongkir.com/basic';
            } else {
                $this->baseUrl = 'https://api.rajaongkir.com/starter';
            }
        }

        // Clean trailing slash from base URL if exists
        $this->baseUrl = rtrim($this->baseUrl, '/');

        Log::debug('RajaOngkirService initialized', [
            'baseUrl' => $this->baseUrl,
            'accountType' => $this->accountType,
            'apiKey_mask' => substr($this->apiKey, 0, 5) . '...' . substr($this->apiKey, -3)
        ]);
    }

    /**
     * Resolve path based on base URL (handle Komerce proxy differences)
     */
    protected function getPath($path, $id = null)
    {
        if (strpos($this->baseUrl, 'komerce.id') !== false) {
            if ($path === 'province') return 'destination/province';
            if ($path === 'city') {
                // Komerce uses path parameter: /destination/city/{province_id}
                return $id ? "destination/city/{$id}" : 'destination/city';
            }
            if ($path === 'district') {
                // Komerce uses path parameter: /destination/district/{city_id}
                return $id ? "destination/district/{$id}" : 'destination/district';
            }
            if ($path === 'subdistrict') {
                // Komerce uses path parameter: /destination/sub-district/{district_id}
                return $id ? "destination/sub-district/{$id}" : 'destination/sub-district';
            }
            if ($path === 'cost') return 'calculate/domestic-cost';
        }
        // Official RajaOngkir structure
        return $path;
    }

    /**
     * Get list of provinces
     */
    public function getProvinces()
    {
        $cacheKey = 'rajaongkir_provinces';
        $cached = Cache::get($cacheKey);
        
        if ($cached && isset($cached['success']) && $cached['success']) {
            return $cached;
        }

        try {
            if (!$this->apiKey) {
                return ['success' => false, 'message' => 'RajaOngkir API Key is not set in .env'];
            }

            $request = Http::withHeaders([
                'key' => $this->apiKey
            ]);
            
            if (app()->environment('local')) {
                $request->withoutVerifying();
            }
            
            $fullUrl = "{$this->baseUrl}/" . $this->getPath('province');
            Log::debug('RajaOngkir requesting URL: ' . $fullUrl);
            $response = $request->get($fullUrl);

            if ($response->successful()) {
                $data = $response->json();
                
                // Handle Official RajaOngkir Structure
                if (isset($data['rajaongkir']['status']['code']) && $data['rajaongkir']['status']['code'] == 200) {
                    $result = [
                        'success' => true,
                        'data' => $data['rajaongkir']['results']
                    ];
                    Cache::put($cacheKey, $result, 86400);
                    return $result;
                }
                
                // Handle Komerce/Alternative Structure (meta/data)
                if (isset($data['meta']['code']) && $data['meta']['code'] == 200 && isset($data['data'])) {
                    $result = [
                        'success' => true,
                        'data' => $data['data']
                    ];
                    Cache::put($cacheKey, $result, 86400);
                    return $result;
                }
                
                // Backup check for general success/data structure
                if (isset($data['success']) && $data['success'] && (isset($data['data']) || isset($data['results']))) {
                    $result = [
                        'success' => true,
                        'data' => $data['data'] ?? $data['results']
                    ];
                    Cache::put($cacheKey, $result, 86400);
                    return $result;
                }
            }

            Log::error('RajaOngkir provinces request failed', [
                'status' => $response->status(),
                'url' => $fullUrl,
                'body' => $response->json()
            ]);

            $errorMessage = 'Failed to fetch provinces (Status: ' . $response->status() . ')';
            if ($response->json()) {
                if (isset($response->json()['rajaongkir']['status']['description'])) {
                    $errorMessage = $response->json()['rajaongkir']['status']['description'];
                } elseif (isset($response->json()['meta']['message'])) {
                    $errorMessage = $response->json()['meta']['message'];
                } elseif (isset($response->json()['message'])) {
                    $errorMessage = $response->json()['message'];
                }
            }

            return ['success' => false, 'message' => $errorMessage];
        } catch (\Exception $e) {
            Log::error('RajaOngkir getProvinces error: ' . $e->getMessage());
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * Get cities by province ID
     */
    public function getCities($provinceId = null)
    {
        $cacheKey = $provinceId ? "rajaongkir_cities_{$provinceId}" : 'rajaongkir_cities_all';
        $cached = Cache::get($cacheKey);

        if ($cached && isset($cached['success']) && $cached['success']) {
            return $cached;
        }

        try {
            if (!$this->apiKey) {
                return ['success' => false, 'message' => 'RajaOngkir API Key is not set in .env'];
            }

            if (strpos($this->baseUrl, 'komerce.id') !== false && $provinceId) {
                // Komerce uses path param for city: destination/city/{provinceId}
                $url = "{$this->baseUrl}/" . $this->getPath('city', $provinceId);
                $params = [];
            } else {
                // Official RajaOngkir uses query param: city?province={provinceId}
                $url = "{$this->baseUrl}/" . $this->getPath('city');
                $params = $provinceId ? ['province' => $provinceId] : [];
            }

            $request = Http::withHeaders([
                'key' => $this->apiKey
            ]);
            
            if (app()->environment('local')) {
                $request->withoutVerifying();
            }
            
            $response = $request->get($url, $params);

            if ($response->successful()) {
                $data = $response->json();
                
                // Handle Official RajaOngkir Structure
                if (isset($data['rajaongkir']['status']['code']) && $data['rajaongkir']['status']['code'] == 200) {
                    $result = [
                        'success' => true,
                        'data' => $data['rajaongkir']['results']
                    ];
                    Cache::put($cacheKey, $result, 86400);
                    return $result;
                }

                // Handle Komerce/Alternative Structure (meta/data)
                if (isset($data['meta']['code']) && $data['meta']['code'] == 200 && isset($data['data'])) {
                    $result = [
                        'success' => true,
                        'data' => $data['data']
                    ];
                    Cache::put($cacheKey, $result, 86400);
                    return $result;
                }

                // Backup check for general success/data structure
                if (isset($data['success']) && $data['success'] && (isset($data['data']) || isset($data['results']))) {
                    $result = [
                        'success' => true,
                        'data' => $data['data'] ?? $data['results']
                    ];
                    Cache::put($cacheKey, $result, 86400);
                    return $result;
                }
            }

            Log::error('RajaOngkir cities request failed', [
                'province_id' => $provinceId,
                'status' => $response->status(),
                'url' => $url,
                'body' => $response->json()
            ]);

            $errorMessage = 'Failed to fetch cities (Status: ' . $response->status() . ')';
            if ($response->json()) {
                if (isset($response->json()['rajaongkir']['status']['description'])) {
                    $errorMessage = $response->json()['rajaongkir']['status']['description'];
                } elseif (isset($response->json()['meta']['message'])) {
                    $errorMessage = $response->json()['meta']['message'];
                } elseif (isset($response->json()['message'])) {
                    $errorMessage = $response->json()['message'];
                }
            }

            return ['success' => false, 'message' => $errorMessage];
        } catch (\Exception $e) {
            Log::error('RajaOngkir getCities error: ' . $e->getMessage());
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * Get districts by city ID
     */
    public function getDistricts($cityId)
    {
        $cacheKey = "rajaongkir_districts_{$cityId}";
        $cached = Cache::get($cacheKey);

        if ($cached && isset($cached['success']) && $cached['success']) {
            return $cached;
        }

        try {
            if (!$this->apiKey) {
                return ['success' => false, 'message' => 'RajaOngkir API Key is not set'];
            }

            $url = "{$this->baseUrl}/" . $this->getPath('district', $cityId);

            $request = Http::withHeaders([
                'key' => $this->apiKey
            ]);
            
            if (app()->environment('local')) {
                $request->withoutVerifying();
            }
            
            $response = $request->get($url);

            if ($response->successful()) {
                $data = $response->json();
                
                // Handle Official RajaOngkir Structure
                if (isset($data['rajaongkir']['status']['code']) && $data['rajaongkir']['status']['code'] == 200) {
                    $result = [
                        'success' => true,
                        'data' => $data['rajaongkir']['results']
                    ];
                    Cache::put($cacheKey, $result, 86400);
                    return $result;
                }
                
                // Handle Komerce/Alternative Structure (meta/data)
                if (isset($data['meta']['code']) && $data['meta']['code'] == 200 && isset($data['data'])) {
                    $result = [
                        'success' => true,
                        'data' => $data['data']
                    ];
                    Cache::put($cacheKey, $result, 86400);
                    return $result;
                }

                // Backup check for general success/data structure
                if (isset($data['success']) && $data['success'] && (isset($data['data']) || isset($data['results']))) {
                    $result = [
                        'success' => true,
                        'data' => $data['data'] ?? $data['results']
                    ];
                    Cache::put($cacheKey, $result, 86400);
                    return $result;
                }
            }

            Log::error('RajaOngkir districts request failed', [
                'city_id' => $cityId,
                'status' => $response->status(),
                'url' => $url,
                'body' => $response->json()
            ]);

            $errorMessage = 'Failed to fetch districts (Status: ' . $response->status() . ')';
            if ($response->json()) {
                if (isset($response->json()['rajaongkir']['status']['description'])) {
                    $errorMessage = $response->json()['rajaongkir']['status']['description'];
                } elseif (isset($response->json()['meta']['message'])) {
                    $errorMessage = $response->json()['meta']['message'];
                } elseif (isset($response->json()['message'])) {
                    $errorMessage = $response->json()['message'];
                }
            }

            return ['success' => false, 'message' => $errorMessage];
        } catch (\Exception $e) {
            Log::error('RajaOngkir getDistricts error: ' . $e->getMessage());
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * Get subdistricts by district ID
     */
    public function getSubdistricts($districtId)
    {
        $cacheKey = "rajaongkir_subdistricts_{$districtId}";
        $cached = Cache::get($cacheKey);

        if ($cached && isset($cached['success']) && $cached['success']) {
            return $cached;
        }

        try {
            if (!$this->apiKey) {
                return ['success' => false, 'message' => 'RajaOngkir API Key is not set'];
            }

            $url = "{$this->baseUrl}/" . $this->getPath('subdistrict', $districtId);

            $request = Http::withHeaders([
                'key' => $this->apiKey
            ]);
            
            if (app()->environment('local')) {
                $request->withoutVerifying();
            }
            
            $response = $request->get($url);

            if ($response->successful()) {
                $data = $response->json();
                
                // Handle Official RajaOngkir Structure
                if (isset($data['rajaongkir']['status']['code']) && $data['rajaongkir']['status']['code'] == 200) {
                    $result = [
                        'success' => true,
                        'data' => $data['rajaongkir']['results']
                    ];
                    Cache::put($cacheKey, $result, 86400);
                    return $result;
                }
                
                // Handle Komerce/Alternative Structure (meta/data)
                if (isset($data['meta']['code']) && $data['meta']['code'] == 200 && isset($data['data'])) {
                    $result = [
                        'success' => true,
                        'data' => $data['data']
                    ];
                    Cache::put($cacheKey, $result, 86400);
                    return $result;
                }

                // Backup check for general success/data structure
                if (isset($data['success']) && $data['success'] && (isset($data['data']) || isset($data['results']))) {
                    $result = [
                        'success' => true,
                        'data' => $data['data'] ?? $data['results']
                    ];
                    Cache::put($cacheKey, $result, 86400);
                    return $result;
                }
            }

            Log::error('RajaOngkir subdistricts request failed', [
                'district_id' => $districtId,
                'status' => $response->status(),
                'url' => $url,
                'body' => $response->json()
            ]);

            $errorMessage = 'Failed to fetch subdistricts (Status: ' . $response->status() . ')';
            if ($response->json()) {
                if (isset($response->json()['rajaongkir']['status']['description'])) {
                    $errorMessage = $response->json()['rajaongkir']['status']['description'];
                } elseif (isset($response->json()['meta']['message'])) {
                    $errorMessage = $response->json()['meta']['message'];
                } elseif (isset($response->json()['message'])) {
                    $errorMessage = $response->json()['message'];
                }
            }

            return ['success' => false, 'message' => $errorMessage];
        } catch (\Exception $e) {
            Log::error('RajaOngkir getSubdistricts error: ' . $e->getMessage());
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * Calculate shipping cost
     * 
     * @param string $origin City ID of origin
     * @param string $destination City ID of destination
     * @param int $weight Weight in grams
     * @param string $courier Courier code (jne, pos, tiki, etc)
     */
    public function calculateCost($origin, $destination, $weight, $courier)
    {
        try {
            $request = Http::withHeaders([
                'key' => $this->apiKey,
                'content-type' => 'application/x-www-form-urlencoded'
            ])->asForm();
            
            if (app()->environment('local')) {
                $request->withoutVerifying();
            }
            
            $url = "{$this->baseUrl}/" . $this->getPath('cost');
            $response = $request->post($url, [
                'origin' => $origin,
                'destination' => $destination,
                'weight' => $weight,
                'courier' => $courier
            ]);

            if ($response->successful()) {
                $data = $response->json();
                
                // Handle Official RajaOngkir Structure
                if (isset($data['rajaongkir']['status']['code']) && $data['rajaongkir']['status']['code'] == 200) {
                    return [
                        'success' => true,
                        'data' => $data['rajaongkir']['results']
                    ];
                }

                // Handle Komerce/Alternative Structure (meta/data)
                if (isset($data['meta']['code']) && $data['meta']['code'] == 200 && isset($data['data'])) {
                    return [
                        'success' => true,
                        'data' => $data['data']
                    ];
                }

                // Backup check for general success/data structure
                if (isset($data['success']) && $data['success'] && (isset($data['data']) || isset($data['results']))) {
                    return [
                        'success' => true,
                        'data' => $data['data'] ?? $data['results']
                    ];
                }
            }

            Log::error('RajaOngkir calculateCost failed', [
                'url' => $url,
                'status' => $response->status(),
                'response' => $response->json()
            ]);

            $errorMessage = 'Failed to calculate shipping cost';
            if ($response->json()) {
                if (isset($response->json()['rajaongkir']['status']['description'])) {
                    $errorMessage = $response->json()['rajaongkir']['status']['description'];
                } elseif (isset($response->json()['meta']['message'])) {
                    $errorMessage = $response->json()['meta']['message'];
                }
            }

            return ['success' => false, 'message' => $errorMessage];
        } catch (\Exception $e) {
            Log::error('RajaOngkir calculateCost error: ' . $e->getMessage());
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * Get supported couriers based on account type
     */
    public function getSupportedCouriers()
    {
        $couriers = [
            'starter' => [
                ['code' => 'jne', 'name' => 'JNE'],
                ['code' => 'pos', 'name' => 'POS Indonesia'],
                ['code' => 'tiki', 'name' => 'TIKI'],
            ],
            'basic' => [
                ['code' => 'jne', 'name' => 'JNE'],
                ['code' => 'pos', 'name' => 'POS Indonesia'],
                ['code' => 'tiki', 'name' => 'TIKI'],
                ['code' => 'rpx', 'name' => 'RPX'],
                ['code' => 'pandu', 'name' => 'Pandu Logistics'],
                ['code' => 'wahana', 'name' => 'Wahana'],
                ['code' => 'sicepat', 'name' => 'SiCepat'],
                ['code' => 'jnt', 'name' => 'J&T Express'],
                ['code' => 'pahala', 'name' => 'Pahala'],
                ['code' => 'sap', 'name' => 'SAP Express'],
                ['code' => 'jet', 'name' => 'JET Express'],
                ['code' => 'indah', 'name' => 'Indah Cargo'],
                ['code' => 'dse', 'name' => 'DSE'],
                ['code' => 'slis', 'name' => 'Solusi Express'],
                ['code' => 'first', 'name' => 'First Logistics'],
                ['code' => 'ncs', 'name' => 'NCS'],
                ['code' => 'star', 'name' => 'Star Cargo'],
                ['code' => 'ninja', 'name' => 'Ninja Express'],
                ['code' => 'lion', 'name' => 'Lion Parcel'],
                ['code' => 'idl', 'name' => 'IDL Cargo'],
                ['code' => 'rex', 'name' => 'REX'],
                ['code' => 'ide', 'name' => 'ID Express'],
                ['code' => 'sentral', 'name' => 'Sentral Cargo'],
                ['code' => 'anteraja', 'name' => 'AnterAja'],
            ],
            'pro' => [
                // Pro has all basic couriers plus more
                ['code' => 'jne', 'name' => 'JNE'],
                ['code' => 'pos', 'name' => 'POS Indonesia'],
                ['code' => 'tiki', 'name' => 'TIKI'],
                ['code' => 'rpx', 'name' => 'RPX'],
                ['code' => 'sicepat', 'name' => 'SiCepat'],
                ['code' => 'jnt', 'name' => 'J&T Express'],
                ['code' => 'anteraja', 'name' => 'AnterAja'],
                ['code' => 'ninja', 'name' => 'Ninja Express'],
                ['code' => 'lion', 'name' => 'Lion Parcel'],
                // Add more as needed
            ]
        ];

        return $couriers[$this->accountType] ?? $couriers['starter'];
    }

    /**
     * Get origin city ID from settings
     */
    public function getOriginCityId()
    {
        // Default origin city from database setting, with fallback to config
        return \App\Models\Setting::getValue('rajaongkir_origin_city', 'website') ?: config('services.rajaongkir.origin_city', '151');
    }
}
