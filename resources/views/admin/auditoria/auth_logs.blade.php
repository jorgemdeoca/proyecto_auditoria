@extends('layouts.admin')

@section('title', 'Log de Autenticación')

@section('content')
<div class="p-4 lg:p-6 space-y-5">

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <nav class="text-xs text-gray-400 mb-1">
                <a href="{{ route('admin.auditoria.index') }}" class="hover:text-emerald-500 transition-colors">Auditoría</a>
                <span class="mx-1">/</span> <span class="text-gray-600 dark:text-gray-300">Log de Accesos</span>
            </nav>
            <h1 class="text-xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
                <i class="bi bi-key-fill text-amber-500"></i> Log de Autenticación
            </h1>
            <p class="text-sm text-gray-400 mt-1">Historial de inicios de sesión, fallos y cierres de sesión.</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.auditoria.exportar.auth.excel', request()->all()) }}"
                class="inline-flex items-center gap-2 px-3 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium rounded-lg transition-all shadow-sm">
                <i class="bi bi-file-earmark-excel-fill"></i> Excel
            </a>
        </div>
    </div>

    @include('admin.auditoria.partials._filtros', [
        'actionUrl' => route('admin.auditoria.auth-logs'),
        'eventos' => [
            'LOGIN_OK'   => 'Acceso Exitoso',
            'LOGIN_FAIL' => 'Intento Fallido',
            'LOGOUT'     => 'Cierre de Sesión',
            'LOCKOUT'    => 'Cuenta Bloqueada',
            'UNLOCK'     => 'Cuenta Desbloqueada',
        ],
        'searchField'       => 'correo',
        'searchLabel'       => 'Correo del usuario',
        'searchPlaceholder' => 'ej: usuario@email.com',
    ])

    {{-- Stats rápidas de la vista actual --}}
    <div class="grid grid-cols-3 sm:grid-cols-5 gap-3">
        @php
            $statData = [
                'LOGIN_OK'   => ['label' => 'Exitosos',  'color' => 'emerald'],
                'LOGIN_FAIL' => ['label' => 'Fallidos',  'color' => 'amber'],
                'LOGOUT'     => ['label' => 'Logouts',   'color' => 'gray'],
                'LOCKOUT'    => ['label' => 'Bloqueos',  'color' => 'rose'],
                'UNLOCK'     => ['label' => 'Desbloqueos', 'color' => 'blue'],
            ];
        @endphp
        @foreach($statData as $key => $stat)
        <a href="{{ route('admin.auditoria.auth-logs') }}?evento={{ $key }}"
            class="bg-white dark:bg-gray-800 rounded-xl p-3 border border-gray-200 dark:border-gray-700 shadow-sm text-center hover:border-{{ $stat['color'] }}-400 transition-all">
            <p class="text-lg font-bold text-gray-900 dark:text-white">
                {{ \App\Models\AuthLog::where('event_type', $key)->count() }}
            </p>
            <p class="text-[11px] text-gray-500">{{ $stat['label'] }}</p>
        </a>
        @endforeach
    </div>

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
                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Evento</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Correo</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Usuario ID</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">IP</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Navegador</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Fecha</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 dark:divide-gray-700">
                    @forelse($registros as $reg)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/40 transition-colors">
                        <td class="px-4 py-3">
                            @php
                                $badgeClass = match($reg->event_type) {
                                    'LOGIN_OK'   => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300',
                                    'LOGOUT'     => 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300',
                                    'LOGIN_FAIL' => 'bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-300',
                                    'LOCKOUT'    => 'bg-rose-100 text-rose-700 dark:bg-rose-900/40 dark:text-rose-300',
                                    'UNLOCK'     => 'bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-300',
                                    default      => 'bg-gray-100 text-gray-600',
                                };
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold {{ $badgeClass }}">
                                {{ $reg->badge_label }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-sm font-medium text-gray-800 dark:text-gray-200">
                            {{ $reg->correo }}
                        </td>
                        <td class="px-4 py-3 text-xs font-mono text-gray-400">
                            {{ $reg->user_id ?? 'N/A' }}
                        </td>
                        <td class="px-4 py-3 text-xs font-mono text-gray-500">
                            {{ $reg->ip_address ?? '-' }}
                        </td>
                        <td class="px-4 py-3 text-xs text-gray-400 max-w-[200px] truncate" title="{{ $reg->user_agent }}">
                            {{ $reg->user_agent ? Str::limit($reg->user_agent, 40) : '-' }}
                        </td>
                        <td class="px-4 py-3 text-xs text-gray-500 whitespace-nowrap">
                            {{ $reg->created_at->format('d/m/Y') }}<br>
                            <span class="text-[10px] text-gray-400">{{ $reg->created_at->format('H:i:s') }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-4 py-10 text-center text-sm text-gray-400">
                            <i class="bi bi-inbox text-3xl mb-2 block"></i>
                            No se encontraron registros de autenticación.
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
