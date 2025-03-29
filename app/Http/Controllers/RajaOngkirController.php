<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RajaOngkirController extends Controller
{
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
        $province = $this->_ongkir_get('province');
        $data = json_decode($province, true);
        
        if (!isset($data['rajaongkir']['results'])) {
            return response()->json(['error' => 'Failed to fetch provinces'], 500);
        }
        
        return response()->json($data['rajaongkir']['results']);
    }
    
    public function city($province_id)
    {
        if (!is_numeric($province_id)) {
            return response()->json(['error' => 'Invalid province ID'], 400);
        }
        
        $city = $this->_ongkir_get('city?province=' . $province_id);
        $data = json_decode($city, true);
        
        if (!isset($data['rajaongkir']['results'])) {
            return response()->json(['error' => 'Failed to fetch cities'], 500);
        }
        
        return response()->json($data['rajaongkir']['results']);
    }

    public function cost($origin, $destination, $quantity, $courier)
    {
        $weight = (int)$quantity * 300; // 300 gram/pieces for every product
        $price = $this->_ongkir_post($origin, $destination, $weight, $courier);
        $data = json_decode($price, true);
        echo json_encode($data['rajaongkir']["results"]);
    }
}
