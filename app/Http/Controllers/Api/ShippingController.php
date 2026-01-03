<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\RajaOngkirService;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ShippingController extends Controller
{
    protected $rajaOngkir;

    public function __construct(RajaOngkirService $rajaOngkir)
    {
        $this->rajaOngkir = $rajaOngkir;
    }

    /**
     * Get list of provinces
     */
    public function provinces()
    {
        $result = $this->rajaOngkir->getProvinces();
        return response()->json($result);
    }

    /**
     * Get list of cities by province
     */
    public function cities($province_id)
    {
        $result = $this->rajaOngkir->getCities($province_id);
        return response()->json($result);
    }

    /**
     * Get list of districts by city
     */
    public function districts($city_id)
    {
        $result = $this->rajaOngkir->getDistricts($city_id);
        return response()->json($result);
    }

    /**
     * Get list of subdistricts by district
     */
    public function subdistricts($district_id)
    {
        $result = $this->rajaOngkir->getSubdistricts($district_id);
        return response()->json($result);
    }

    /**
     * Calculate shipping cost
     */
    public function calculate(Request $request)
    {
        $request->validate([
            'destination' => 'required|string',
            'weight' => 'required|integer|min:1',
            'courier' => 'required|string',
        ]);

        $origin = $this->rajaOngkir->getOriginCityId();
        
        $result = $this->rajaOngkir->calculateCost(
            $origin,
            $request->destination,
            $request->weight,
            $request->courier
        );

        return response()->json($result);
    }

    /**
     * Validate free shipping code
     */
    public function validateCode(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
        ]);

        $savedCode = Setting::getValue('free_shipping_code', 'website');
        
        if (!empty($savedCode) && strtoupper($request->code) === strtoupper($savedCode)) {
            return response()->json([
                'success' => true,
                'message' => 'Kode voucher berhasil digunakan!',
                'discount' => 'FREE_SHIPPING'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Kode voucher tidak valid atau sudah kedaluwarsa.'
        ], 422);
    }
}
