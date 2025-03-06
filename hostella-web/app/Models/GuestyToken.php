<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class GuestyToken extends Model
{
    use HasFactory;

    protected $fillable = [
        'access_token',
        'token_type',
        'expires_at',
        'is_active'
    ];

    /**
     * Verifica si el token ha expirado (Si quedan menos de 1 hora, se considera expirado)
     */
    public function hasExpired()
    {
        return Carbon::parse($this->expires_at)->lt(now()->addHour());
    }

    /**
     * Obtiene el token activo más reciente o null si no hay ninguno válido.
     */
    public static function getActiveToken()
    {
        // Obtener el token activo más reciente
        $token = self::where('is_active', true)
                     ->orderBy('created_at', 'desc')
                     ->first();
    
        // Log para verificar si se encontró un token en la base de datos
        if ($token) {
            Log::info("Token encontrado en la BD: {$token->access_token}, Expira en: {$token->expires_at}");
        } else {
            Log::warning("No se encontró un token activo en la BD.");
        }
    
        // Validar si el token aún es válido
        if (!$token || Carbon::parse($token->expires_at)->lt(now())) {
            Log::warning("El token encontrado ha expirado.");
            return null;
        }
    
        return $token;
    }
    

    /**
     * Almacena un nuevo token en la base de datos, desactivando los anteriores.
     */
    public static function storeNewToken($accessToken, $tokenType, $expiresIn)
    {
        // Desactivar todos los tokens anteriores
        self::where('is_active', true)->update(['is_active' => false]);

        // Guardar nuevo token con tiempo de expiración de 23 horas (para renovar 1 hora antes)
        return self::create([
            'access_token' => $accessToken,
            'token_type' => $tokenType,
            'expires_at' => now()->addSeconds($expiresIn - 3600), // Se renueva 1 hora antes de expirar
            'is_active' => true,
        ]);
    }
}
