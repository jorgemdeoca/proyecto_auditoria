<?php

namespace App\Exports;

use App\Models\AuditLog;

/**
 * Exportación de Auditoría de Citas compatible con maatwebsite/excel v1.x
 * Uso: Excel::create('nombre', function($excel) { $excel->sheet('Hoja', function($sheet) {
 *          $sheet->fromArray((new AuditCitasExport($filtros))->collection()->toArray());
 *      });
 * })->download('xlsx');
 *
 * En esta versión se usa como clase de colección simple para pasar al controlador.
 */
class AuditCitasExport
{
    public function __construct(private array $filtros = []) {}

    public function headings(): array
    {
        return [
            'ID', 'Tipo Registro', 'ID Registro', 'Evento',
            'Realizado Por', 'Rol', 'IP', 'Valores Anteriores', 'Valores Nuevos', 'Fecha',
        ];
    }

    public function rows(): array
    {
        $query = AuditLog::delModulo('citas')->latest('created_at');

        if (!empty($this->filtros['desde']) && !empty($this->filtros['hasta'])) {
            $query->enRango($this->filtros['desde'], $this->filtros['hasta']);
        }
        if (!empty($this->filtros['evento'])) {
            $query->where('event', $this->filtros['evento']);
        }

        return $query->limit(5000)->get()->map(function ($row) {
            return [
                $row->id,
                class_basename($row->auditable_type),
                $row->auditable_id,
                strtoupper($row->event),
                $row->causer_nombre ?? 'Sistema',
                $row->causer_rol ?? '-',
                $row->ip_address ?? '-',
                $row->old_values ? json_encode(json_decode($row->old_values), JSON_UNESCAPED_UNICODE) : '-',
                $row->new_values ? json_encode(json_decode($row->new_values), JSON_UNESCAPED_UNICODE) : '-',
                $row->created_at?->format('d/m/Y H:i:s'),
            ];
        })->toArray();
    }

    public function sheetTitle(): string { return 'Auditoría Citas'; }
    public function filename(): string   { return 'auditoria_citas_' . now()->format('Ymd_His'); }
}
