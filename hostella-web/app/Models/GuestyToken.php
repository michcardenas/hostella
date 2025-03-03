<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

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
     * Verifica si el token ha expirado
     */
    public function hasExpired()
    {
        if (!$this->expires_at) {
            return false;
        }
        
        return $this->expires_at <= time();
    }

    /**
     * Obtiene el token activo más reciente o null si no hay ninguno válido
     */
    public static function getActiveToken()
    {
        $token = self::where('is_active', true)
                    ->orderBy('created_at', 'desc')
                    ->first();
        
        if (!$token || $token->hasExpired()) {
            return null;
        }
        
        return $token;
    }
}