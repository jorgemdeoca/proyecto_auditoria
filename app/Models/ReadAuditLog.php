<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReadAuditLog extends Model
{
    public $timestamps = false; // Tabla append-only: solo created_at
    protected $table   = 'read_audit_logs';
    protected $guarded = [];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    // ── Relación ─────────────────────────────────────────────────────────────

    public function lector()
    {
        return $this->belongsTo(Usuario::class, 'reader_id');
    }

    // ── Scopes ───────────────────────────────────────────────────────────────

    public function scopeEnRango($query, string $desde, string $hasta)
    {
        return $query->whereBetween('created_at', [
            $desde . ' 00:00:00',
            $hasta . ' 23:59:59',
        ]);
    }

    public function scopePorTipo($query, string $resourceType)
    {
        return $query->where('resource_type', $resourceType);
    }

    public function scopePorLector($query, int $readerId)
    {
        return $query->where('reader_id', $readerId);
    }

    public function scopePorPaciente($query, int $pacienteId)
    {
        return $query->where('paciente_id', $pacienteId);
    }

    // ── Accessors para las vistas Blade ──────────────────────────────────────

    public function getBadgeColorAttribute(): string
    {
        return match ($this->resource_type) {
            'HistoriaClinicaBase' => 'info',
            'EvolucionClinica'   => 'primary',
            default              => 'secondary',
        };
    }

    public function getTipoLegibleAttribute(): string
    {
        return match ($this->resource_type) {
            'HistoriaClinicaBase' => 'Historia Clínica Base',
            'EvolucionClinica'   => 'Evolución Clínica',
            default              => $this->resource_type,
        };
    }
}
