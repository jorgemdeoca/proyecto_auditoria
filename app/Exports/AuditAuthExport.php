<?php

namespace App\Exports;

use App\Models\AuthLog;

class AuditAuthExport
{
    public function __construct(private array $filtros = []) {}

    public function headings(): array
    {
        return ['ID', 'Usuario ID', 'Correo', 'Evento', 'IP', 'Navegador', 'Fecha'];
    }

    public function rows(): array
    {
        $query = AuthLog::latest('created_at');

        if (!empty($this->filtros['desde']) && !empty($this->filtros['hasta'])) {
            $query->enRango($this->filtros['desde'], $this->filtros['hasta']);
        }
        if (!empty($this->filtros['evento'])) {
            $query->porEvento($this->filtros['evento']);
        }
        if (!empty($this->filtros['correo'])) {
            $query->where('correo', 'like', '%' . $this->filtros['correo'] . '%');
        }

        return $query->limit(5000)->get()->map(function ($row) {
            return [
                $row->id,
                $row->user_id ?? 'N/A',
                $row->correo,
                $row->badge_label,
                $row->ip_address ?? '-',
                $row->user_agent ? substr($row->user_agent, 0, 80) : '-',
                $row->created_at?->format('d/m/Y H:i:s'),
            ];
        })->toArray();
    }

    public function sheetTitle(): string { return 'Log de Accesos'; }
    public function filename(): string   { return 'auditoria_accesos_' . now()->format('Ymd_His'); }
}
