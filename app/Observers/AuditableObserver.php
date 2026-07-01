<?php

namespace App\Observers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AuditableObserver
{
    /**
     * Registro de un nuevo modelo.
     */
    public function created(Model $model): void
    {
        $this->registrar($model, 'created', [], $model->getAttributes());
    }

    /**
     * Registro de una modificación.
     * Detecta automáticamente si es un cambio de estado de cita (state_changed).
     */
    public function updated(Model $model): void
    {
        $exclude = method_exists($model, 'getAuditExclude')
            ? $model->getAuditExclude()
            : ['updated_at', 'status'];

        // Solo campos que realmente cambiaron y no están excluidos
        $camposModificados = collect($model->getDirty())
            ->except($exclude)
            ->keys()
            ->all();

        // Si no cambió nada relevante, no registrar
        if (empty($camposModificados)) {
            return;
        }

        // Detectar cambio de estado para un evento más descriptivo
        $event = 'updated';
        if (in_array('estado_cita', $camposModificados)) {
            $event = 'state_changed';
        }
        if (in_array('estado', $camposModificados) && str_contains(get_class($model), 'Pago')) {
            $event = 'payment_state_changed';
        }
        if (in_array('status_factura', $camposModificados)) {
            $event = 'invoice_state_changed';
        }

        $oldValues = collect($model->getOriginal())->only($camposModificados)->all();
        $newValues = collect($model->getAttributes())->only($camposModificados)->all();

        $this->registrar($model, $event, $oldValues, $newValues);
    }

    /**
     * Registro de eliminación (soft o hard delete).
     */
    public function deleted(Model $model): void
    {
        $this->registrar($model, 'deleted', $model->getOriginal(), []);
    }

    /**
     * Método central de escritura. Usa DB::table() directamente para:
     * 1. No disparar este mismo observer en una recursión infinita.
     * 2. Evitar el overhead de Eloquent en cada log.
     */
    private function registrar(Model $model, string $event, array $old, array $new): void
    {
        try {
            $user   = Auth::user();
            $nombre = 'Sistema';

            if ($user) {
                $nombre = method_exists($user, 'getNombreCompletoAttribute')
                    ? $user->nombre_completo
                    : $user->correo ?? "Usuario #{$user->id}";
            }

            $modulo = method_exists($model, 'getAuditModulo')
                ? $model->getAuditModulo()
                : strtolower(class_basename($model));

            // Capturar motivo_cambio si viene en el request actual
            $motivo = request()?->input('motivo_cambio')
                   ?? request()?->input('motivo')
                   ?? request()?->input('observaciones_cancelacion');

            DB::table('audit_logs')->insert([
                'auditable_type' => get_class($model),
                'auditable_id'   => $model->getKey(),
                'causer_id'      => $user?->id,
                'causer_nombre'  => $nombre,
                'event'          => $event,
                'modulo'         => $modulo,
                'old_values'     => !empty($old) ? json_encode($old, JSON_UNESCAPED_UNICODE) : null,
                'new_values'     => !empty($new) ? json_encode($new, JSON_UNESCAPED_UNICODE) : null,
                'motivo'         => $motivo,
                'ip_address'     => request()?->ip(),
                'session_id'     => session()?->getId(),
                'created_at'     => now(),
            ]);
        } catch (\Throwable $e) {
            // El fallo en auditoría NO debe interrumpir la operación principal.
            \Log::warning('[AuditableObserver] No se pudo registrar la traza.', [
                'model'   => get_class($model),
                'id'      => $model->getKey(),
                'event'   => $event,
                'error'   => $e->getMessage(),
            ]);
        }
    }
}
