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
     * Filtra por el lector que accedió a la historia.
     */
    public function scopePorLector($query, int $readerId)
    {
        return $query->where('reader_id', $readerId);
    }

    /**
     * Filtra por paciente cuya historia fue leída.
     */
    public function scopePorPaciente($query, int $pacienteId)
    {
        return $query->where('paciente_id', $pacienteId);
    }

    /**
     * Filtra por tipo de recurso accedido (HistoriaClinicaBase, EvolucionClinica).
     */
    public function scopePorTipo($query, string $type)
    {
        return $query->where('resource_type', $type);
    }
}
