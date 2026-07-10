<?php

namespace App\Exports;

use App\Models\AuditLog;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AuditCitasExport implements FromArray, WithHeadings, WithStyles
{
    public function __construct(private array $filtros = []) {}

    public function headings(): array
    {
        return [
            'ID', 'Tipo Registro', 'ID Registro', 'Evento',
            'Realizado Por', 'Rol', 'IP', 'Valores Anteriores', 'Valores Nuevos', 'Fecha',
        ];
    }

    public function array(): array
    {
        $query = AuditLog::delModulo('citas')->latest('created_at');

        if (!empty($this->filtros['desde']) && !empty($this->filtros['hasta'])) {
            $query->enRango($this->filtros['desde'], $this->filtros['hasta']);
        } elseif (!empty($this->filtros['desde'])) {
            $query->whereDate('created_at', '>=', $this->filtros['desde']);
        } elseif (!empty($this->filtros['hasta'])) {
            $query->whereDate('created_at', '<=', $this->filtros['hasta']);
        }

        if (!empty($this->filtros['evento'])) {
            $query->where('event', $this->filtros['evento']);
        }

        if (!empty($this->filtros['registro_id'])) {
            $query->where('auditable_id', $this->filtros['registro_id']);
        }

        return $query->limit(5000)->get()->map(function ($row) {
            return [
                $row->id,
                class_basename($row->auditable_type),
                $row->auditable_id,
                strtoupper($row->event_translated),
                $row->causer_nombre ?? 'Sistema',
                $row->causer_rol,
                $row->ip_address ?? '-',
                \App\Models\AuditLog::formatValues($row->old_values),
                \App\Models\AuditLog::formatValues($row->new_values),
                $row->created_at?->format('d/m/Y H:i:s'),
            ];
        })->toArray();
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
                'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'color' => ['argb' => 'FF10B981']]
            ],
        ];
    }
}
