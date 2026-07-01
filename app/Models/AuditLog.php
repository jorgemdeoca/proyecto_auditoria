<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    public $timestamps = false; // Solo created_at
    protected $table   = 'audit_logs';
    protected $guarded = [];

    protected $casts = [
        'created_at' => 'datetime',
        'old_values' => 'array',
        'new_values' => 'array',
    ];

    // ── Scopes ───────────────────────────────────────────────────────────────

    /**
     * Filtra por módulo (citas, pagos, facturacion, historia_clinica, configuracion)
     */
    public function scopeDelModulo($query, string $modulo)
    {
        return $query->where('modulo', $modulo);
    }

    /**
     * Filtra por rango de fechas
     */
    public function scopeEnRango($query, string $desde, string $hasta)
    {
        return $query->whereBetween('created_at', [
            $desde . ' 00:00:00',
            $hasta . ' 23:59:59',
        ]);
    }

    /**
     * Filtra por tipo de evento
     */
    public function scopePorEvento($query, string $event)
    {
        return $query->where('event', $event);
    }

    /**
     * Filtra por usuario causante
     */
    public function scopePorCauser($query, int $causerId)
    {
        return $query->where('causer_id', $causerId);
    }

    /**
     * Filtra por tipo de modelo auditado
     */
    public function scopePorTipo($query, string $type)
    {
        return $query->where('auditable_type', $type);
    }

    // ── Relaciones ────────────────────────────────────────────────────────────

    public function causer()
    {
        return $this->belongsTo(Usuario::class, 'causer_id');
    }

    // ── Accessors ─────────────────────────────────────────────────────────────

    public function getEventLabelAttribute(): string
    {
        return match ($this->event) {
            'created'      => 'Creado',
            'updated'      => 'Modificado',
            'deleted'      => 'Eliminado',
            'state_changed'=> 'Estado cambiado',
            default        => ucfirst($this->event),
        };
    }

    public function getBadgeClassAttribute(): string
    {
        return match ($this->event) {
            'created'      => 'bg-emerald-100 text-emerald-700',
            'updated'      => 'bg-blue-100 text-blue-700',
            'deleted'      => 'bg-rose-100 text-rose-700',
            'state_changed'=> 'bg-purple-100 text-purple-700',
            default        => 'bg-gray-100 text-gray-600',
        };
    }

    public function getTipoLegibleAttribute(): string
    {
        return match (class_basename($this->auditable_type ?? '')) {
            'Cita'                => 'Cita Médica',
            'Pago'                => 'Pago',
            'FacturaPaciente'     => 'Factura Paciente',
            'HistoriaClinicaBase' => 'Historia Clínica',
            'EvolucionClinica'    => 'Evolución Clínica',
            'TasaDolar'           => 'Tasa de Cambio',
            default               => class_basename($this->auditable_type ?? 'Desconocido'),
        };
    }
}
