@extends('layouts.admin')

@section('title', 'Auditoría y Seguridad')

@section('content')
<div class="p-4 lg:p-6 space-y-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-emerald-500/10 flex items-center justify-center">
                    <i class="bi bi-shield-lock-fill text-emerald-500 text-xl"></i>
                </div>
                Auditoría y Seguridad
            </h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                Monitoreo centralizado de cambios, accesos y autenticación del sistema.
                @if($isRoot)
                    <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-300">
                        <i class="bi bi-star-fill mr-1"></i> Root — Vista Global
                    </span>
                @else
                    <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300">
                        <i class="bi bi-building mr-1"></i> Vista filtrada por consultorios asignados
                    </span>
                @endif
            </p>
        </div>
        <div class="text-xs text-gray-400 dark:text-gray-500 text-right hidden sm:block">
            <i class="bi bi-clock-history mr-1"></i> Actualizado: {{ now()->format('d/m/Y H:i') }}
        </div>
    </div>

    {{-- KPI Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-5 gap-4">
        {{-- Eventos Citas --}}
        <a href="{{ route('admin.auditoria.citas') }}"
            class="group bg-white dark:bg-gray-800 rounded-xl p-4 border border-gray-200 dark:border-gray-700 shadow-sm hover:shadow-md hover:border-emerald-300 dark:hover:border-emerald-700 transition-all duration-200">
            <div class="flex items-center justify-between mb-3">
                <div class="w-9 h-9 rounded-lg bg-emerald-100 dark:bg-emerald-900/40 flex items-center justify-center">
                    <i class="bi bi-calendar-check-fill text-emerald-600 dark:text-emerald-400"></i>
                </div>
                <i class="bi bi-arrow-right text-gray-300 dark:text-gray-600 group-hover:text-emerald-500 transition-colors"></i>
            </div>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($totalEventosCitas) }}</p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Cambios en Citas</p>
        </a>

        {{-- Eventos Pagos --}}
        <a href="{{ route('admin.auditoria.pagos') }}"
            class="group bg-white dark:bg-gray-800 rounded-xl p-4 border border-gray-200 dark:border-gray-700 shadow-sm hover:shadow-md hover:border-blue-300 dark:hover:border-blue-700 transition-all duration-200">
            <div class="flex items-center justify-between mb-3">
                <div class="w-9 h-9 rounded-lg bg-blue-100 dark:bg-blue-900/40 flex items-center justify-center">
                    <i class="bi bi-credit-card-fill text-blue-600 dark:text-blue-400"></i>
                </div>
                <i class="bi bi-arrow-right text-gray-300 dark:text-gray-600 group-hover:text-blue-500 transition-colors"></i>
            </div>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($totalEventosPagos) }}</p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Cambios en Pagos</p>
        </a>

        {{-- Accesos Historia Clínica --}}
        <a href="{{ route('admin.auditoria.acceso-medico') }}"
            class="group bg-white dark:bg-gray-800 rounded-xl p-4 border border-gray-200 dark:border-gray-700 shadow-sm hover:shadow-md hover:border-teal-300 dark:hover:border-teal-700 transition-all duration-200">
            <div class="flex items-center justify-between mb-3">
                <div class="w-9 h-9 rounded-lg bg-teal-100 dark:bg-teal-900/40 flex items-center justify-center">
                    <i class="bi bi-file-earmark-medical-fill text-teal-600 dark:text-teal-400"></i>
                </div>
                <i class="bi bi-arrow-right text-gray-300 dark:text-gray-600 group-hover:text-teal-500 transition-colors"></i>
            </div>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($totalLecturasHistorias) }}</p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Lecturas de Historias</p>
        </a>

        {{-- Login Fallidos --}}
        <a href="{{ route('admin.auditoria.auth-logs') }}?evento=LOGIN_FAIL"
            class="group bg-white dark:bg-gray-800 rounded-xl p-4 border border-gray-200 dark:border-gray-700 shadow-sm hover:shadow-md hover:border-amber-300 dark:hover:border-amber-700 transition-all duration-200">
            <div class="flex items-center justify-between mb-3">
                <div class="w-9 h-9 rounded-lg bg-amber-100 dark:bg-amber-900/40 flex items-center justify-center">
                    <i class="bi bi-exclamation-triangle-fill text-amber-600 dark:text-amber-400"></i>
                </div>
                <i class="bi bi-arrow-right text-gray-300 dark:text-gray-600 group-hover:text-amber-500 transition-colors"></i>
            </div>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($totalLoginFallidos) }}</p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Intentos Fallidos (30d)</p>
        </a>

        {{-- Cuentas Bloqueadas --}}
        <a href="{{ route('admin.auditoria.auth-logs') }}?evento=LOCKOUT"
            class="group bg-white dark:bg-gray-800 rounded-xl p-4 border border-gray-200 dark:border-gray-700 shadow-sm hover:shadow-md hover:border-rose-300 dark:hover:border-rose-700 transition-all duration-200">
            <div class="flex items-center justify-between mb-3">
                <div class="w-9 h-9 rounded-lg bg-rose-100 dark:bg-rose-900/40 flex items-center justify-center">
                    <i class="bi bi-lock-fill text-rose-600 dark:text-rose-400"></i>
                </div>
                <i class="bi bi-arrow-right text-gray-300 dark:text-gray-600 group-hover:text-rose-500 transition-colors"></i>
            </div>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($totalCuentasBloqueadas) }}</p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Bloqueos (30d)</p>
        </a>
    </div>

    {{-- Tendencia del Sistema (Gráfica) --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm p-5">
        <div class="flex items-center justify-between mb-4">
            <h2 class="font-semibold text-gray-800 dark:text-white flex items-center gap-2">
                <i class="bi bi-graph-up text-emerald-500"></i> Tendencia de Actividad (Últimos 7 días)
            </h2>
        </div>
        <div class="relative h-64 w-full">
            <canvas id="tendenciaChart"></canvas>
        </div>
    </div>

    {{-- Dos columnas: Últimos eventos + Auth logs recientes --}}
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">

        {{-- Últimos Eventos de Entidades --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
                <h2 class="font-semibold text-gray-800 dark:text-white flex items-center gap-2">
                    <i class="bi bi-activity text-emerald-500"></i> Últimos Cambios en el Sistema
                </h2>
                <a href="{{ route('admin.auditoria.citas') }}"
                    class="text-xs text-emerald-600 hover:underline font-medium">Ver todos</a>
            </div>
            <div class="divide-y divide-gray-50 dark:divide-gray-700">
                @forelse($ultimosEventos as $evento)
                <div class="px-5 py-3 flex items-start gap-3 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                    <div class="mt-0.5">
                        @php
                            $iconMap = ['created' => 'plus-circle-fill text-emerald-500', 'updated' => 'pencil-square text-blue-500', 'deleted' => 'trash-fill text-rose-500', 'state_changed' => 'arrow-left-right text-purple-500'];
                            $icon = $iconMap[$evento->event] ?? 'circle-fill text-gray-400';
                        @endphp
                        <i class="bi bi-{{ $icon }} text-sm"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm text-gray-800 dark:text-gray-200 truncate">
                            <span class="font-medium">{{ class_basename($evento->auditable_type) }} #{{ $evento->auditable_id }}</span>
                            <span class="text-gray-400 mx-1">·</span>
                            @php
                                $eventName = match($evento->event) {
                                    'created' => 'Creado',
                                    'updated' => 'Modificado',
                                    'deleted' => 'Eliminado',
                                    'state_changed' => 'Cambio de Estado (Cita)',
                                    'payment_state_changed' => 'Cambio de Estado (Pago)',
                                    'invoice_state_changed' => 'Cambio de Estado (Factura)',
                                    default => ucfirst($evento->event),
                                };
                            @endphp
                            <span class="capitalize">{{ $eventName }}</span>
                        </p>
                        <p class="text-xs text-gray-400 mt-0.5">
                            {{ $evento->causer_nombre ?? 'Sistema' }} · {{ $evento->created_at->diffForHumans() }}
                        </p>
                    </div>
                    <span class="text-[10px] text-gray-400 whitespace-nowrap">{{ $evento->created_at->format('H:i') }}</span>
                </div>
                @empty
                <div class="px-5 py-8 text-center text-sm text-gray-400">
                    <i class="bi bi-inbox text-2xl mb-2 block"></i>
                    Sin eventos registrados todavía.
                </div>
                @endforelse
            </div>
        </div>

        {{-- Auth Logs Recientes --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
                <h2 class="font-semibold text-gray-800 dark:text-white flex items-center gap-2">
                    <i class="bi bi-person-check-fill text-amber-500"></i> Actividad de Autenticación
                </h2>
                <a href="{{ route('admin.auditoria.auth-logs') }}"
                    class="text-xs text-amber-600 hover:underline font-medium">Ver todos</a>
            </div>
            <div class="divide-y divide-gray-50 dark:divide-gray-700">
                @forelse($ultimosAuthLogs as $log)
                <div class="px-5 py-3 flex items-center gap-3 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                    <div>
                        @php
                            $badgeClass = match($log->event_type) {
                                'LOGIN_OK'   => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300',
                                'LOGOUT'     => 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300',
                                'LOGIN_FAIL' => 'bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-300',
                                'LOCKOUT'    => 'bg-rose-100 text-rose-700 dark:bg-rose-900/40 dark:text-rose-300',
                                default      => 'bg-blue-100 text-blue-700',
                            };
                        @endphp
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold {{ $badgeClass }}">
                            {{ $log->badge_label }}
                        </span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm text-gray-800 dark:text-gray-200 truncate">{{ $log->correo }}</p>
                        <p class="text-xs text-gray-400">{{ $log->ip_address }} · {{ $log->created_at->diffForHumans() }}</p>
                    </div>
                    <span class="text-[10px] text-gray-400">{{ $log->created_at->format('H:i') }}</span>
                </div>
                @empty
                <div class="px-5 py-8 text-center text-sm text-gray-400">
                    <i class="bi bi-inbox text-2xl mb-2 block"></i>
                    Sin registros de autenticación.
                </div>
                @endforelse
            </div>
        </div>
    </div>


</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('tendenciaChart');
    if (!ctx) return;

    const chartData = @json($chartData ?? ['labels' => [], 'data' => []]);
    const isDarkMode = document.documentElement.classList.contains('dark');
    const textColor = isDarkMode ? '#9ca3af' : '#6b7280';
    const gridColor = isDarkMode ? '#374151' : '#f3f4f6';

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: chartData.labels,
            datasets: [{
                label: 'Eventos Auditados',
                data: chartData.data,
                borderColor: '#10b981',
                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                borderWidth: 2,
                tension: 0.4,
                fill: true,
                pointBackgroundColor: '#10b981',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 4,
                pointHoverRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: isDarkMode ? '#1f2937' : '#fff',
                    titleColor: isDarkMode ? '#fff' : '#111827',
                    bodyColor: isDarkMode ? '#d1d5db' : '#4b5563',
                    borderColor: isDarkMode ? '#374151' : '#e5e7eb',
                    borderWidth: 1,
                    padding: 10,
                    displayColors: false,
                    callbacks: {
                        label: function(context) {
                            return context.parsed.y + ' eventos registrados';
                        }
                    }
                }
            },
            scales: {
                x: {
                    grid: { display: false },
                    ticks: { color: textColor, font: { size: 11 } }
                },
                y: {
                    grid: { color: gridColor },
                    ticks: { color: textColor, font: { size: 11 }, precision: 0 },
                    beginAtZero: true
                }
            },
            interaction: {
                intersect: false,
                mode: 'index',
            },
        }
    });
});
</script>
@endsection
