<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AuditReadAccess
{
    /**
     * Registra silenciosamente cuándo alguien visualiza una historia clínica.
     * Uso en rutas: ->middleware('audit.read:HistoriaClinicaBase,paciente')
     *
     * @param  string  $resourceType  Ej: HistoriaClinicaBase, EvolucionClinica
     * @param  string  $pacienteParam Nombre del parámetro de ruta (ej: 'paciente', 'id')
     */
    public function handle(Request $request, Closure $next, string $resourceType, string $pacienteParam = 'paciente')
    {
        // Primero dejamos que la petición se procese normalmente
        $response = $next($request);

        // Solo auditamos si la petición fue exitosa (2xx)
        if ($response->isSuccessful()) {
            try {
                $user = auth()->user();
                $nombreLector = 'Desconocido';

                if ($user) {
                    $nombreLector = method_exists($user, 'getNombreCompletoAttribute')
                        ? $user->nombre_completo
                        : $user->correo;
                }

                $rolLector = $user?->rol?->nombre ?? '';

                // Extraer el ID del recurso de la ruta
                $recurso = $request->route($pacienteParam) ?? $request->route('id');
                $recursoId = is_object($recurso) ? $recurso->getKey() : (int) $recurso;

                // Intentar obtener datos del paciente
                $pacienteId = $recursoId;
                $pacienteNombre = null;

                if (is_object($recurso) && isset($recurso->paciente)) {
                    $pacienteNombre = $recurso->paciente->primer_nombre . ' ' . $recurso->paciente->primer_apellido;
                    $pacienteId = $recurso->paciente->id;
                } elseif (is_object($recurso) && method_exists($recurso, 'getPrimerNombreAttribute')) {
                    $pacienteNombre = $recurso->primer_nombre . ' ' . $recurso->primer_apellido;
                }

                DB::table('read_audit_logs')->insert([
                    'reader_id'       => $user?->id ?? 0,
                    'reader_nombre'   => $nombreLector,
                    'reader_rol'      => $rolLector,
                    'resource_type'   => $resourceType,
                    'resource_id'     => $recursoId,
                    'paciente_id'     => $pacienteId,
                    'paciente_nombre' => $pacienteNombre,
                    'ip_address'      => $request->ip(),
                    'ruta_accedida'   => $request->route()?->getName(),
                    'created_at'      => now(),
                ]);
            } catch (\Throwable $e) {
                // Falla silenciosa: la auditoría nunca bloquea al usuario
                \Log::warning('[AuditReadAccess] Error: ' . $e->getMessage());
            }
        }

        return $response;
    }
}
