<?php

namespace App\Traits;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;

trait HasAuditTrail
{
    /**
     * Boot the trait and register model event listeners.
     */
    protected static function bootHasAuditTrail(): void
    {
        static::created(function ($model) {
            static::registrarEvento($model, 'created', [], $model->getAttributes());
        });

        static::updated(function ($model) {
            $cambios = $model->getChanges();
            // Excluir campos técnicos
            unset($cambios['updated_at']);
            if (empty($cambios)) return;

            $anterior = array_intersect_key($model->getOriginal(), $cambios);
            static::registrarEvento($model, 'updated', $anterior, $cambios);
        });

        static::deleted(function ($model) {
            static::registrarEvento($model, 'deleted', $model->getAttributes(), []);
        });
    }

    /**
     * Inserta el registro en audit_logs de forma silenciosa.
     */
    protected static function registrarEvento($model, string $event, array $oldValues, array $newValues): void
    {
        try {
            $user = Auth::user();

            // Determinar el módulo a partir del nombre de la clase
            $modulo = static::resolverModulo(class_basename($model));

            \DB::table('audit_logs')->insert([
                'auditable_type' => get_class($model),
                'auditable_id'   => $model->getKey(),
                'causer_id'      => $user?->id,
                'causer_nombre'  => $user?->nombre_completo ?? 'Sistema',
                'causer_rol'     => $user?->rol?->nombre ?? '',
                'event'          => $event,
                'modulo'         => $modulo,
                'old_values'     => empty($oldValues) ? null : json_encode($oldValues),
                'new_values'     => empty($newValues) ? null : json_encode($newValues),
                'ip_address'     => request()->ip(),
                'created_at'     => now(),
            ]);
        } catch (\Throwable $e) {
            // Falla silenciosa: la auditoría nunca bloquea al usuario
            \Log::warning('[HasAuditTrail] Error registrando evento: ' . $e->getMessage());
        }
    }

    /**
     * Resuelve el nombre del módulo a partir del modelo.
     */
    protected static function resolverModulo(string $className): string
    {
        return match ($className) {
            'Cita'                => 'citas',
            'Pago'                => 'pagos',
            'FacturaPaciente'     => 'facturacion',
            'HistoriaClinicaBase' => 'historia_clinica',
            'EvolucionClinica'    => 'historia_clinica',
            'TasaDolar'           => 'configuracion',
            default               => strtolower($className),
        };
    }
}
