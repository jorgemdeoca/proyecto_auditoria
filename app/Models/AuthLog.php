<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuthLog extends Model
{
    public $timestamps = false; // Solo created_at
    protected $table   = 'auth_logs';
    protected $guarded = [];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    // ── Scopes ───────────────────────────────────────────────────────────────

    public function scopePorEvento($query, string $eventType)
    {
        return $query->where('event_type', $eventType);
    }

    public function scopeEnRango($query, string $desde, string $hasta)
    {
        return $query->whereBetween('created_at', [
            $desde . ' 00:00:00',
            $hasta . ' 23:59:59',
        ]);
    }

    public function scopeSoloFallidos($query)
    {
        return $query->where('event_type', 'LOGIN_FAIL');
    }

    public function scopeSoloBloqueos($query)
    {
        return $query->where('event_type', 'LOCKOUT');
    }

    public function scopeRecientes($query, int $dias = 30)
    {
        return $query->where('created_at', '>=', now()->subDays($dias));
    }

    // ── Relaciones ────────────────────────────────────────────────────────────

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'user_id');
    }

    // ── Accessors ─────────────────────────────────────────────────────────────

    public function getBadgeLabelAttribute(): string
    {
        return match ($this->event_type) {
            'LOGIN_OK'   => '✓ Acceso OK',
            'LOGIN_FAIL' => '✗ Fallido',
            'LOGOUT'     => '→ Logout',
            'LOCKOUT'    => '🔒 Bloqueado',
            'UNLOCK'     => '🔓 Desbloqueado',
            default      => $this->event_type,
        };
    }

    public function getBadgeColorAttribute(): string
    {
        return match ($this->event_type) {
            'LOGIN_OK'   => 'emerald',
            'LOGIN_FAIL' => 'amber',
            'LOGOUT'     => 'gray',
            'LOCKOUT'    => 'rose',
            'UNLOCK'     => 'blue',
            default      => 'gray',
        };
    }

    public function getEsRiesosoAttribute(): bool
    {
        return in_array($this->event_type, ['LOGIN_FAIL', 'LOCKOUT']);
    }
}
