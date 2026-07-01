<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuthLog extends Model
{
    public $timestamps = false; // Tabla append-only: solo created_at
    protected $table   = 'auth_logs';
    protected $guarded = [];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    // ── Relación ─────────────────────────────────────────────────────────────

    /**
     * Relación opcional al usuario (puede ser null si el correo no existe).
     */
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'user_id');
    }

    // ── Scopes ───────────────────────────────────────────────────────────────

    /**
     * Filtra por rango de fechas.
     */
    public function scopeEnRango($query, string $desde, string $hasta)
    {
        return $query->whereBetween('created_at', [
            $desde . ' 00:00:00',
            $hasta . ' 23:59:59',
        ]);
    }

    /**
     * Filtra por tipo de evento (LOGIN_OK, LOGIN_FAIL, LOGOUT, LOCKOUT, UNLOCK).
     */
    public function scopePorEvento($query, string $eventType)
    {
        return $query->where('event_type', $eventType);
    }

    /**
     * Filtra por usuario específico.
     */
    public function scopePorUsuario($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Filtra por dirección IP.
     */
    public function scopePorIp($query, string $ip)
    {
        return $query->where('ip_address', $ip);
    }

    // ── Accessors para las vistas Blade ──────────────────────────────────────

    /**
     * Retorna el color del badge según el tipo de evento.
     */
    public function getBadgeColorAttribute(): string
    {
        return match($this->event_type) {
            'LOGIN_OK'   => 'success',
            'LOGOUT'     => 'secondary',
            'LOGIN_FAIL' => 'warning',
            'LOCKOUT'    => 'danger',
            'UNLOCK'     => 'info',
            default      => 'secondary',
        };
    }

    /**
     * Retorna la etiqueta legible del evento.
     */
    public function getBadgeLabelAttribute(): string
    {
        return match($this->event_type) {
            'LOGIN_OK'   => 'Acceso Exitoso',
            'LOGOUT'     => 'Cierre de Sesión',
            'LOGIN_FAIL' => 'Intento Fallido',
            'LOCKOUT'    => 'Cuenta Bloqueada',
            'UNLOCK'     => 'Cuenta Desbloqueada',
            default      => $this->event_type,
        };
    }
}
