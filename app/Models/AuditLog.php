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

    // ── Atributos Dinámicos y Formateadores (Legibilidad) ────────────────────

    public function getCauserRolAttribute()
    {
        if (!$this->causer_id) return 'Sistema';
        
        $user = Usuario::with(['rol', 'administrador', 'medico', 'paciente'])->find($this->causer_id);
        if (!$user) return 'Desconocido';

        if ($user->administrador) {
            return 'Administrador: ' . ucfirst($user->administrador->tipo_admin);
        } elseif ($user->medico) {
            return 'Médico';
        } elseif ($user->paciente) {
            return 'Paciente';
        }
        return $user->rol->nombre ?? 'Usuario';
    }

    public function getEventTranslatedAttribute()
    {
        $map = [
            'created' => 'Creado',
            'updated' => 'Actualizado',
            'deleted' => 'Eliminado',
            'state_changed' => 'Estado Modificado',
            'payment_state_changed' => 'Estado de Pago Modificado',
            'invoice_state_changed' => 'Estatus de Factura Modificado',
            'restored' => 'Restaurado',
        ];
        return $map[$this->event] ?? ucfirst($this->event);
    }

    public static function formatValues($valuesArray)
    {
        if (empty($valuesArray) || !is_array($valuesArray)) {
            return '-';
        }

        $lines = [];
        $confirmadoPorStr = null;

        $keyMap = [
            'estado' => 'Estado',
            'status' => 'Estatus',
            'id_pago' => 'ID Pago',
            'id_metodo' => 'Método de Pago',
            'created_at' => 'Fecha Creación',
            'fecha_pago' => 'Fecha Pago',
            'referencia' => 'Referencia',
            'updated_at' => 'Fecha Actualización',
            'comentarios' => 'Comentarios',
            'comprobante' => 'Comprobante',
            'monto_pagado_bs' => 'Monto Pagado (Bs)',
            'tasa_aplicada_id' => 'Tasa Aplicada (Bs)',
            'id_factura_paciente' => 'ID Factura',
            'monto_equivalente_usd' => 'Monto Equiv. (USD)',
            'confirmado_por' => 'Confirmado Por',
            'status_factura' => 'Estatus Factura',
            'cita_id' => 'ID Cita',
            'tasa_id' => 'Tasa de Cambio (Bs)',
            'monto_bs' => 'Monto (Bs)',
            'medico_id' => 'Médico',
            'monto_usd' => 'Monto (USD)',
            'paciente_id' => 'Paciente',
            'fecha_emision' => 'Fecha Emisión',
            'numero_factura' => 'Nº Factura',
            'estado_cita' => 'Estado de Cita',
        ];

        foreach ($valuesArray as $k => $v) {
            // Omitir comprobante (ruta) ya que la referencia es más útil visualmente
            if ($k === 'comprobante') continue;

            $label = $keyMap[$k] ?? ucfirst(str_replace('_', ' ', $k));
            $val = $v;

            if (is_bool($v)) {
                $val = $v ? 'Activo/Sí' : 'Inactivo/No';
            } elseif (is_null($v)) {
                $val = 'Vacío';
            } elseif ($k === 'confirmado_por' && is_numeric($v)) {
                $admin = Administrador::find($v);
                if ($admin) {
                    $val = $admin->nombre_completo . ' (' . $admin->tipo_documento . '-' . $admin->numero_documento . ')';
                }
                $confirmadoPorStr = "$label: $val";
                continue; // Lo añadimos al principio después
            } elseif ($k === 'medico_id' && is_numeric($v)) {
                $medico = Medico::find($v);
                if ($medico) {
                    $val = $medico->primer_nombre . ' ' . $medico->primer_apellido;
                }
            } elseif ($k === 'paciente_id' && is_numeric($v)) {
                $pac = Paciente::find($v);
                if ($pac) {
                    $val = $pac->primer_nombre . ' ' . $pac->primer_apellido;
                }
            } elseif ($k === 'id_metodo' && is_numeric($v)) {
                $metodo = MetodoPago::find($v);
                if ($metodo) {
                    $val = $metodo->nombre;
                }
            } elseif (in_array($k, ['tasa_aplicada_id', 'tasa_id']) && is_numeric($v)) {
                $tasa = TasaDolar::find($v);
                if ($tasa) {
                    $val = number_format($tasa->valor, 2, ',', '.') . ' Bs';
                }
            }
            
            $lines[] = "$label: $val";
        }

        // Colocar "Confirmado Por" al principio si existe
        if ($confirmadoPorStr) {
            array_unshift($lines, $confirmadoPorStr);
        }

        return implode(' | ', $lines);
    }
}
