
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
