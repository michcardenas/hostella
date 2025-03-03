<?php

namespace App\Services;

use App\Models\GuestyToken;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GuestyTokenService
{
    protected $authUrl;
    protected $clientId;
    protected $clientSecret;
    
    public function __construct()
    {
        $this->authUrl = 'https://booking.guesty.com/oauth2/token';
        $this->clientId = config('services.guesty.client_id');
        $this->clientSecret = config('services.guesty.client_secret');
    }
    
    /**
     * Obtiene un token activo o genera uno nuevo si es necesario
     */
    public function getToken()
    {
        // Buscar token activo en la base de datos
        $token = GuestyToken::getActiveToken();
        
        // Si hay un token válido, lo devolvemos
        if ($token) {
            return $token->access_token;
        }
        
        // Si no hay token válido, generamos uno nuevo
        return $this->refreshToken();
    }
    
    /**
     * Refresca el token llamando a la API de Guesty
     */
    public function refreshToken()
    {
        try {
            Log::info('Refrescando token de Guesty');
            
            // Aquí iría la lógica para obtener un nuevo token
            // Esta parte depende de la documentación específica de Guesty sobre cómo renovar tokens
            $response = Http::post($this->authUrl, [
                'grant_type' => 'client_credentials',
                'client_id' => $this->clientId,
                'client_secret' => $this->clientSecret,
            ]);
            
            if ($response->successful()) {
                $data = $response->json();
                
                // Extraer información del token
                $accessToken = $data['access_token'];
                $expiresIn = $data['expires_in'] ?? 86400; // 24 horas por defecto
                $tokenType = $data['token_type'] ?? 'Bearer';
                
                // Desactivar tokens anteriores
                GuestyToken::where('is_active', true)->update(['is_active' => false]);
                
                // Guardar el nuevo token
                $token = GuestyToken::create([
                    'access_token' => $accessToken,
                    'token_type' => $tokenType,
                    'expires_at' => time() + $expiresIn - 300, // 5 minutos antes para seguridad
                    'is_active' => true,
                ]);
                
                Log::info('Token de Guesty refrescado exitosamente');
                
                return $accessToken;
            } else {
                Log::error('Error al refrescar token de Guesty: ' . $response->body());
                
                // Si no podemos obtener un nuevo token, usamos el último que tengamos
                // aunque esté expirado (mejor que nada)
                $lastToken = GuestyToken::orderBy('created_at', 'desc')->first();
                return $lastToken ? $lastToken->access_token : null;
            }
        } catch (\Exception $e) {
            Log::error('Excepción al refrescar token de Guesty: ' . $e->getMessage());
            return null;
        }
    }
}