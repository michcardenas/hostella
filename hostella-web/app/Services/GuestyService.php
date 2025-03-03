<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Services\GuestyTokenService;

class GuestyService
{
    protected $baseUrl;
    protected $tokenService;
    protected $tokenType;

    public function __construct(GuestyTokenService $tokenService)
    {
        $this->baseUrl = config('services.guesty.base_url', 'https://booking.guesty.com/api');
        $this->tokenService = $tokenService;
        $this->tokenType = config('services.guesty.token_type', 'Bearer');
    }

    /**
     * Get all listings from Guesty API
     */
    public function getListings($params = [])
    {
        return $this->makeRequest('GET', '/listings', $params);
    }

    /**
     * Get a specific listing by ID
     */
    public function getListing($id)
    {
        return $this->makeRequest('GET', "/listings/{$id}");
    }

    /**
     * Make API request to Guesty
     */
    protected function makeRequest($method, $endpoint, $queryParams = [], $data = null)
    {
        $url = $this->baseUrl . $endpoint;
        
        // Obtener el token actualizado
        $token = $this->tokenService->getToken();
        
        if (!$token) {
            throw new \Exception('No se pudo obtener un token válido para Guesty API');
        }
        
        Log::debug("Guesty API Request", [
            'method' => $method,
            'url' => $url,
            'params' => $queryParams
        ]);
        
        $response = Http::withHeaders([
            'Authorization' => "{$this->tokenType} {$token}",
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ]);

        if ($method === 'GET') {
            $response = $response->get($url, $queryParams);
        } elseif ($method === 'POST') {
            $response = $response->post($url, $data);
        } elseif ($method === 'PUT') {
            $response = $response->put($url, $data);
        } elseif ($method === 'DELETE') {
            $response = $response->delete($url, $data);
        }

        Log::debug("Guesty API Response", [
            'status' => $response->status(),
            'success' => $response->successful()
        ]);

        if ($response->failed()) {
            Log::error("Guesty API Error", [
                'status' => $response->status(),
                'body' => $response->body()
            ]);
            throw new \Exception('Guesty API Error: ' . $response->status() . ' - ' . $response->body());
        }

        return $response->json();
    }
}