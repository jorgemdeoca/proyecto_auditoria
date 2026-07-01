<?php

namespace App\Exports;

use App\Models\AuditLog;

class AuditPagosExport
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
        $query = AuditLog::delModulo('pagos')->latest('created_at');

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

    public function sheetTitle(): string { return 'Auditoría Pagos'; }
    public function filename(): string   { return 'auditoria_pagos_' . now()->format('Ymd_His'); }
}
