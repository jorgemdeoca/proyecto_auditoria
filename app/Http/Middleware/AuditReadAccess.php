<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class AuditReadAccess
{
    /**
     * Registra en read_audit_logs cada vez que un médico accede a una historia clínica.
     *
     * Uso en rutas:
     *   Route::get('/historia/{pacienteId}', ...)->middleware('audit.read:HistoriaClinicaBase');
     *
     * El parámetro de la ruta debe tener el ID del paciente o del recurso.
     */
    public function handle(Request $request, Closure $next, string $resourceType = 'HistoriaClinicaBase'): Response
    {
        $response = $next($request);

        // Solo registrar accesos exitosos (200-299)
        if ($response->isSuccessful()) {
            $this->registrarAcceso($request, $resourceType);
        }

        return $response;
    }

    private function registrarAcceso(Request $request, string $resourceType): void
    {
        try {
            $user   = Auth::user();
            $params = $request->route()?->parameters() ?? [];

            // Intentar extraer el ID del recurso y el paciente de los parámetros de la ruta
            $resourceId = $params['id']
                ?? $params[lcfirst($resourceType)]
                ?? $params['pacienteId']
                ?? $params['paciente']
                ?? 0;

            $pacienteId = $params['pacienteId']
                ?? $params['paciente_id']
                ?? $params['paciente']
                ?? null;

            // Nombre del paciente si lo podemos resolver
            $pacienteNombre = null;
            if ($pacienteId) {
                $paciente = \App\Models\Paciente::find($pacienteId);
                $pacienteNombre = $paciente?->nombre_completo ?? null;
            }

            DB::table('read_audit_logs')->insert([
                'reader_id'       => $user?->id ?? 0,
                'reader_nombre'   => $user?->nombre_completo ?? 'Desconocido',
                'reader_rol'      => $user?->rol?->nombre ?? 'medico',
                'resource_type'   => $resourceType,
                'resource_id'     => is_numeric($resourceId) ? (int) $resourceId : 0,
                'paciente_id'     => $pacienteId ? (int) $pacienteId : null,
                'paciente_nombre' => $pacienteNombre,
                'ip_address'      => $request->ip(),
                'ruta_accedida'   => $request->route()?->getName(),
                'created_at'      => now(),
            ]);
        } catch (\Throwable $e) {
            Log::warning('[AuditReadAccess] No se pudo registrar acceso: ' . $e->getMessage());
        }
    }
}
