<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class RajaOngkirController extends Controller
{
    private $apiKey;
    private $baseUrl = 'https://api.rajaongkir.com/starter/';

    public function __construct()
    {
        $this->apiKey = env('RAJAONGKIR_API_KEY');
    }

    /**
     * GET request to RajaOngkir API
     */
    function _ongkir_get($endpoint)
    {
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => "https://api.rajaongkir.com/starter/" . $endpoint,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTPHEADER => [
                "key: " . env("API_RAJAONGKIR")
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        if ($err) {
            throw new \Exception("CURL Error: " . $err);
        }

        if ($httpCode !== 200) {
            throw new \Exception("RajaOngkir API returned HTTP $httpCode");
        }

        $decoded = json_decode($response, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception("Invalid JSON response");
        }

        if (!isset($decoded['rajaongkir']['results'])) {
            throw new \Exception("Invalid API response structure");
        }

        return $decoded['rajaongkir']['results'];
    }

    /**
     * POST request to RajaOngkir API (for cost calculation)
     */
    function _ongkir_post($origin, $destination, $weight, $courier)
    {
        $postData = http_build_query([
            'origin' => $origin,
            'destination' => $destination,
            'weight' => $weight,
            'courier' => $courier
        ]);

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => "https://api.rajaongkir.com/starter/cost",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $postData,
            CURLOPT_HTTPHEADER => [
                "content-type: application/x-www-form-urlencoded",
                "key: " . env("API_RAJAONGKIR")
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        if ($err) {
            throw new \Exception("CURL Error: " . $err);
        }

        if ($httpCode !== 200) {
            throw new \Exception("RajaOngkir API returned HTTP $httpCode");
        }

        $decoded = json_decode($response, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception("Invalid JSON response");
        }

        if (!isset($decoded['rajaongkir']['results'])) {
            throw new \Exception("Invalid API response structure");
        }

        return $decoded['rajaongkir']['results'];
    }

    public function province()
    {
        try {
            if (!$this->apiKey) {
                return response()->json([
                    'success' => false,
                    'message' => 'RajaOngkir API key tidak ditemukan',
                    'data' => []
                ]);
            }

            $response = Http::withHeaders([
                'key' => $this->apiKey
            ])->get($this->baseUrl . 'province');

            $result = $response->json();

            if (!isset($result['rajaongkir']['results'])) {
                throw new \Exception('Format response tidak valid');
            }

            return response()->json([
                'success' => true,
                'data' => $result['rajaongkir']['results']
            ]);

        } catch (\Exception $e) {
            \Log::error('RajaOngkir province error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data provinsi',
                'data' => []
            ]);
        }
    }
    
    public function city($province_id)
    {
        try {
            if (!$this->apiKey) {
                return response()->json([
                    'success' => false,
                    'message' => 'RajaOngkir API key tidak ditemukan',
                    'data' => []
                ]);
            }

            $response = Http::withHeaders([
                'key' => $this->apiKey
            ])->get($this->baseUrl . 'city', [
                'province' => $province_id
            ]);

            $result = $response->json();

            if (!isset($result['rajaongkir']['results'])) {
                throw new \Exception('Format response tidak valid');
            }

            return response()->json([
                'success' => true,
                'data' => $result['rajaongkir']['results']
            ]);

        } catch (\Exception $e) {
            \Log::error('RajaOngkir city error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data kota',
                'data' => []
            ]);
        }
    }

    public function cost($origin, $destination, $weight, $courier)
    {
        try {
            if (!$this->apiKey) {
                return response()->json([
                    'success' => false,
                    'message' => 'RajaOngkir API key tidak ditemukan',
                    'data' => []
                ]);
            }

            $response = Http::withHeaders([
                'key' => $this->apiKey
            ])->post($this->baseUrl . 'cost', [
                'origin' => $origin,
                'destination' => $destination,
                'weight' => $weight,
                'courier' => $courier
            ]);

            $result = $response->json();

            if (!isset($result['rajaongkir']['results'])) {
                throw new \Exception('Format response tidak valid');
            }

            // Jika tidak ada API key, gunakan ongkir default
            if (!$this->apiKey) {
                return response()->json([
                    'success' => true,
                    'data' => [
                        [
                            'code' => 'DEFAULT',
                            'name' => 'Pengiriman Standard',
                            'costs' => [
                                [
                                    'service' => 'REG',
                                    'description' => 'Reguler',
                                    'cost' => [
                                        [
                                            'value' => 10000,
                                            'etd' => '2-3',
                                            'note' => 'Estimasi pengiriman 2-3 hari'
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]);
            }

            return response()->json([
                'success' => true,
                'data' => $result['rajaongkir']['results']
            ]);

        } catch (\Exception $e) {
            \Log::error('RajaOngkir cost error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghitung ongkos kirim',
                'data' => []
            ]);
        }
    }
}
