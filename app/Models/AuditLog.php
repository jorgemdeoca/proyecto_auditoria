<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    public $timestamps  = false; // Tabla append-only: solo created_at
    protected $table    = 'audit_logs';
    protected $guarded  = [];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
        'created_at' => 'datetime',
    ];

    // ── Scopes reutilizables para el AuditoriaController ─────────────────────

    /**
     * Filtra por módulo (citas, pagos, historia_clinica, configuracion).
     */
    public function scopeDelModulo($query, string $modulo)
    {
        return $query->where('modulo', $modulo);
    }

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
     * Filtra por el usuario que realizó la acción.
     */
    public function scopePorCauser($query, int $causerId)
    {
        return $query->where('causer_id', $causerId);
    }

    /**
     * Filtra por tipo de evento (created, updated, deleted, state_changed).
     */
    public function scopePorEvento($query, string $event)
    {
        return $query->where('event', $event);
    }

    /**
     * Filtra audit_logs de citas que pertenecen a determinados consultorios.
     * Usado por Admin local (solo ve sus consultorios asignados).
     */
    public function scopePorConsultorios($query, array $consultorioIds)
    {
        $citaIds = \DB::table('citas')
            ->whereIn('consultorio_id', $consultorioIds)
            ->pluck('id');

        return $query->where('auditable_type', 'App\Models\Cita')
                     ->whereIn('auditable_id', $citaIds);
    }

    /**
     * Filtra por un consultorio específico (usado por Root al aplicar filtro manual).
     */
    public function scopePorConsultorio($query, int $consultorioId)
    {
        $citaIds = \DB::table('citas')
            ->where('consultorio_id', $consultorioId)
            ->pluck('id');

        return $query->where('auditable_type', 'App\Models\Cita')
                     ->whereIn('auditable_id', $citaIds);
    }
}
