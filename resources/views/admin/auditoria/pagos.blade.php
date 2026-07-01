@extends('layouts.admin')

@section('title', 'Auditoría — Pagos')

@section('content')
<div class="p-4 lg:p-6 space-y-5">

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <nav class="text-xs text-gray-400 mb-1">
                <a href="{{ route('admin.auditoria.index') }}" class="hover:text-emerald-500 transition-colors">Auditoría</a>
                <span class="mx-1">/</span> <span class="text-gray-600 dark:text-gray-300">Pagos</span>
            </nav>
            <h1 class="text-xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
                <i class="bi bi-cash-stack text-blue-500"></i> Auditoría de Pagos
            </h1>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.auditoria.exportar.pagos.excel', request()->all()) }}"
                class="inline-flex items-center gap-2 px-3 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium rounded-lg transition-all shadow-sm">
                <i class="bi bi-file-earmark-excel-fill"></i> Excel
            </a>
            <a href="{{ route('admin.auditoria.exportar.pagos.pdf', request()->all()) }}"
                class="inline-flex items-center gap-2 px-3 py-2 bg-rose-600 hover:bg-rose-700 text-white text-sm font-medium rounded-lg transition-all shadow-sm">
                <i class="bi bi-file-earmark-pdf-fill"></i> PDF
            </a>
        </div>
    </div>

    @include('admin.auditoria.partials._filtros', [
        'actionUrl' => route('admin.auditoria.pagos'),
        'eventos' => ['created' => 'Creado', 'updated' => 'Modificado', 'deleted' => 'Eliminado'],
    ])

    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
        <div class="px-5 py-3 border-b border-gray-100 dark:border-gray-700">
            <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">
                {{ number_format($registros->total()) }} registros encontrados
            </span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 dark:bg-gray-700/50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Registro</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Evento</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Realizado por</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">IP</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Valores Nuevos</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Fecha</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 dark:divide-gray-700">
                    @forelse($registros as $reg)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/40 transition-colors">
                        <td class="px-4 py-3 text-gray-400 text-xs">{{ $reg->id }}</td>
                        <td class="px-4 py-3 text-xs font-mono text-gray-600 dark:text-gray-400">
                            {{ class_basename($reg->auditable_type) }} #{{ $reg->auditable_id }}
                        </td>
                        <td class="px-4 py-3">
                            @php
                                $badgeClass = match($reg->event) {
                                    'created' => 'bg-emerald-100 text-emerald-700',
                                    'updated' => 'bg-blue-100 text-blue-700',
                                    'deleted' => 'bg-rose-100 text-rose-700',
                                    default   => 'bg-gray-100 text-gray-600',
                                };
                            @endphp
                            <span class="inline-flex px-2 py-0.5 rounded-full text-[11px] font-semibold {{ $badgeClass }}">
                                {{ ucfirst($reg->event) }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <p class="font-medium text-gray-800 dark:text-gray-200 text-xs">{{ $reg->causer_nombre ?? 'Sistema' }}</p>
                            <p class="text-[10px] text-gray-400">{{ $reg->causer_rol ?? '' }}</p>
                        </td>
                        <td class="px-4 py-3 text-xs font-mono text-gray-400">{{ $reg->ip_address ?? '-' }}</td>
                        <td class="px-4 py-3 max-w-xs">
                            @if($reg->new_values)
                                <details class="cursor-pointer">
                                    <summary class="text-xs text-blue-500 hover:underline select-none">Ver valores</summary>
                                    <pre class="mt-1 text-[10px] bg-gray-50 dark:bg-gray-900 p-2 rounded text-gray-600 dark:text-gray-400 overflow-auto max-h-24">{{ json_encode(json_decode($reg->new_values), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                </details>
                            @else
                                <span class="text-xs text-gray-300">—</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-xs text-gray-500 whitespace-nowrap">
                            {{ $reg->created_at->format('d/m/Y') }}<br>
                            <span class="text-[10px] text-gray-400">{{ $reg->created_at->format('H:i:s') }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-4 py-10 text-center text-sm text-gray-400">
                            <i class="bi bi-inbox text-3xl mb-2 block"></i>
                            No se encontraron registros.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($registros->hasPages())
        <div class="px-5 py-4 border-t border-gray-100 dark:border-gray-700">
            {{ $registros->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
