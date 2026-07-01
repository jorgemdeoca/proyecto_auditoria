@extends('layouts.admin')

@section('title', 'Acceso a Historias Clínicas')

@section('content')
<div class="p-4 lg:p-6 space-y-5">

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <nav class="text-xs text-gray-400 mb-1">
                <a href="{{ route('admin.auditoria.index') }}" class="hover:text-emerald-500 transition-colors">Auditoría</a>
                <span class="mx-1">/</span> <span class="text-gray-600 dark:text-gray-300">Acceso a Historias</span>
            </nav>
            <h1 class="text-xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
                <i class="bi bi-file-earmark-person text-teal-500"></i> Acceso a Historias Clínicas
            </h1>
            <p class="text-sm text-gray-400 mt-1">Registro de quién abrió o consultó historias clínicas de pacientes.</p>
        </div>
    </div>

    @include('admin.auditoria.partials._filtros', [
        'actionUrl' => route('admin.auditoria.acceso-medico'),
        'eventos' => [
            'HistoriaClinicaBase' => 'Historia Clínica Base',
            'EvolucionClinica'    => 'Evolución Clínica',
        ],
        'eventoParam' => 'tipo',
    ])

    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
        <div class="px-5 py-3 border-b border-gray-100 dark:border-gray-700">
            <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">
                {{ number_format($registros->total()) }} accesos registrados
            </span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 dark:bg-gray-700/50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Tipo de Recurso</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">ID Recurso</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Lector</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Rol</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Paciente</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">IP</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Ruta</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Fecha</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 dark:divide-gray-700">
                    @forelse($registros as $reg)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/40 transition-colors">
                        <td class="px-4 py-3">
                            @php
                                $badgeClass = $reg->resource_type === 'HistoriaClinicaBase'
                                    ? 'bg-teal-100 text-teal-700 dark:bg-teal-900/40 dark:text-teal-300'
                                    : 'bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-300';
                            @endphp
                            <span class="inline-flex px-2 py-0.5 rounded-full text-[11px] font-semibold {{ $badgeClass }}">
                                {{ $reg->tipo_legible }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-xs font-mono text-gray-400">#{{ $reg->resource_id }}</td>
                        <td class="px-4 py-3">
                            <p class="font-medium text-gray-800 dark:text-gray-200 text-xs">{{ $reg->reader_nombre ?? 'Desconocido' }}</p>
                        </td>
                        <td class="px-4 py-3 text-xs text-gray-500">{{ $reg->reader_rol ?? '-' }}</td>
                        <td class="px-4 py-3">
                            <p class="text-xs text-gray-700 dark:text-gray-300">{{ $reg->paciente_nombre ?? 'N/A' }}</p>
                            <p class="text-[10px] text-gray-400">ID: {{ $reg->paciente_id }}</p>
                        </td>
                        <td class="px-4 py-3 text-xs font-mono text-gray-400">{{ $reg->ip_address ?? '-' }}</td>
                        <td class="px-4 py-3 text-xs text-gray-400 truncate max-w-[120px]" title="{{ $reg->ruta_accedida }}">
                            {{ $reg->ruta_accedida ?? '-' }}
                        </td>
                        <td class="px-4 py-3 text-xs text-gray-500 whitespace-nowrap">
                            {{ $reg->created_at->format('d/m/Y') }}<br>
                            <span class="text-[10px] text-gray-400">{{ $reg->created_at->format('H:i:s') }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-4 py-10 text-center text-sm text-gray-400">
                            <i class="bi bi-inbox text-3xl mb-2 block"></i>
                            No se han registrado accesos a historias clínicas.
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
