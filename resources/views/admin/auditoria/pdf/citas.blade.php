<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Auditoría de Citas</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 9px; color: #1f2937; background: #fff; }
        .header { background: #10b981; color: #fff; padding: 14px 20px; margin-bottom: 14px; }
        .header h1 { font-size: 16px; font-weight: bold; margin-bottom: 2px; }
        .header p  { font-size: 9px; opacity: 0.85; }
        .meta { display: flex; gap: 20px; padding: 0 20px 10px; font-size: 8.5px; color: #6b7280; }
        table { width: 100%; border-collapse: collapse; font-size: 8px; }
        thead tr { background: #f0fdf4; }
        thead th { padding: 6px 8px; text-align: left; font-weight: bold; text-transform: uppercase; color: #065f46; border-bottom: 2px solid #6ee7b7; letter-spacing: 0.03em; }
        tbody tr:nth-child(even) { background: #f9fafb; }
        tbody td { padding: 5px 8px; border-bottom: 1px solid #e5e7eb; vertical-align: top; }
        .badge { display: inline-block; padding: 1px 5px; border-radius: 9999px; font-size: 7.5px; font-weight: bold; }
        .badge-created { background: #d1fae5; color: #065f46; }
        .badge-updated { background: #dbeafe; color: #1e40af; }
        .badge-deleted { background: #fee2e2; color: #991b1b; }
        .badge-state { background: #ede9fe; color: #5b21b6; }
        .footer { margin-top: 10px; padding: 6px 20px; font-size: 7.5px; color: #9ca3af; text-align: right; border-top: 1px solid #e5e7eb; }
        .pre { font-family: monospace; font-size: 7px; color: #374151; white-space: pre-wrap; word-break: break-all; max-height: 40px; overflow: hidden; }
    </style>
</head>
<body>
    <div class="header">
        <h1>🔒 Auditoría de Citas Médicas</h1>
        <p>Reporte generado el {{ now()->format('d/m/Y H:i:s') }} · Sistema de Reservas Médicas</p>
    </div>
    <div class="meta">
        <span>Total de registros: <strong>{{ $registros->count() }}</strong></span>
        <span>Módulo: <strong>Citas</strong></span>
    </div>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Tipo</th>
                <th>ID Reg.</th>
                <th>Evento</th>
                <th>Realizado por</th>
                <th>Rol</th>
                <th>IP</th>
                <th>Valores Anteriores</th>
                <th>Fecha</th>
            </tr>
        </thead>
        <tbody>
            @forelse($registros as $reg)
            <tr>
                <td>{{ $reg->id }}</td>
                <td>{{ class_basename($reg->auditable_type) }}</td>
                <td>#{{ $reg->auditable_id }}</td>
                <td>
                    @php $cls = match($reg->event) { 'created' => 'created', 'updated' => 'updated', 'deleted' => 'deleted', default => 'state' }; @endphp
                    <span class="badge badge-{{ $cls }}">{{ $reg->event_translated }}</span>
                </td>
                <td>{{ $reg->causer_nombre ?? 'Sistema' }}</td>
                <td>{{ $reg->causer_rol }}</td>
                <td>{{ $reg->ip_address ?? '-' }}</td>
                <td>
                    @if($reg->old_values)
                        <div class="pre">{{ \App\Models\AuditLog::formatValues($reg->old_values) }}</div>
                    @else — @endif
                </td>
                <td>{{ $reg->created_at->format('d/m/Y H:i') }}</td>
            </tr>
            @empty
            <tr><td colspan="9" style="text-align:center; padding:20px; color:#9ca3af;">Sin registros</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="footer">Página 1 · Generado automáticamente por el Sistema de Auditoría</div>
</body>
</html>
