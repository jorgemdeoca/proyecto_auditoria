@php
    $admin = auth()->user()->administrador ?? null;
    $temaDinamico = $admin ? ($admin->tema_dinamico ?? false) : false;
    $baseColor = '#10b981'; // Default
    $textColorOnPrimary = '#ffffff';

    if ($temaDinamico && $admin->banner_color) {
        $color = $admin->banner_color;
        if (str_starts_with($color, '#')) {
            $baseColor = $color;
        } else {
            preg_match('/from-([a-z]+)-(\d+)/', $color, $matches);
            if (isset($matches[1])) {
                $tailwindColors = [
                    'emerald' => '#10b981',
                    'teal' => '#14b8a6',
                    'blue' => '#3b82f6',
                    'indigo' => '#6366f1',
                    'purple' => '#a855f7',
                    'rose' => '#f43f5e',
                    'slate' => '#64748b',
                    'orange' => '#f97316',
                    'sky' => '#0ea5e9'
                ];
                $baseColor = $tailwindColors[$matches[1]] ?? $baseColor;
            }
        }
        $hex = str_replace('#', '', $baseColor);
        $r = hexdec(strlen($hex) == 3 ? $hex[0] . $hex[0] : substr($hex, 0, 2));
        $g = hexdec(strlen($hex) == 3 ? $hex[1] . $hex[1] : substr($hex, 2, 2));
        $b = hexdec(strlen($hex) == 3 ? $hex[2] . $hex[2] : substr($hex, 4, 2));
        $luminance = ($r * 0.299 + $g * 0.587 + $b * 0.114) / 255;
        $textColorOnPrimary = $luminance > 0.6 ? '#0f172a' : '#ffffff';

        // Versión muy oscura para el sidebar (10% de brillo)
        $darkSidebar = sprintf("#%02x%02x%02x", max(0, $r * 0.15), max(0, $g * 0.15), max(0, $b * 0.15));
    }
    $sidebarBg = isset($darkSidebar) ? "linear-gradient(180deg, $darkSidebar 0%, #020617 100%)" : "linear-gradient(180deg, #0f172a 0%, #020617 100%)";
@endphp
<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Sistema Médico') }} - @yield('title')</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <script>
        document.documentElement.classList.remove('no-js');
        document.documentElement.classList.add('js');
    </script>

    @if($temaDinamico)
        <style>
            :root {
                --medical-500:
                    {{ $baseColor }}
                ;
                --medical-600:
                    {{ $baseColor }}
                    cc;
                --medical-400:
                    {{ $baseColor }}
                    eb;
                --medical-200:
                    {{ $baseColor }}
                    33;
                --medical-50:
                    {{ $baseColor }}
                    1a;
                --text-on-medical:
                    {{ $textColorOnPrimary }}
                ;
                --sidebar-bg:
                    {{ $sidebarBg }}
                ;
            }

            .bg-medical-500 {
                background-color: var(--medical-500) !important;
            }

            .text-medical-500 {
                color: var(--medical-500) !important;
            }

            .text-medical-600 {
                color: var(--medical-600) !important;
            }

            .bg-medical-50 {
                background-color: var(--medical-50) !important;
            }

            .border-medical-500 {
                border-color: var(--medical-500) !important;
            }

            .shadow-medical-200 {
                --tw-shadow-color: var(--medical-200) !important;
            }

            @keyframes float-orb {

                0%,
                100% {
                    transform: translate(0, 0) scale(1);
                }

                33% {
                    transform: translate(30px, -50px) scale(1.1);
                }

                66% {
                    transform: translate(-20px, 20px) scale(0.9);
                }
            }

            .animate-float-orb {
                animation: float-orb 15s ease-in-out infinite;
            }

            .animate-float-orb-slow {
                animation: float-orb 25s ease-in-out infinite reverse;
            }

            .animate-float-orb-delayed {
                animation: float-orb 20s ease-in-out infinite;
                animation-delay: -5s;
            }

            /* Toast Animations */
            .toast-card {
                transform: translateX(400px);
                opacity: 0;
                transition: all 0.5s cubic-bezier(0.68, -0.55, 0.265, 1.55);
            }

            .toast-card.show {
                transform: translateX(0);
                opacity: 1;
            }
        </style>
    @endif

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')
</head>

<body
    class="min-h-screen bg-smoke-50 dark:bg-gray-900 font-sans antialiased transition-colors duration-200 overflow-x-hidden">
    <!-- Premium Mesh Background -->
    <div class="fixed inset-0 z-0 pointer-events-none overflow-hidden">
        <div class="absolute -top-[10%] -left-[10%] w-[50%] h-[50%] rounded-full animate-float-orb blur-[120px]"
            style="background-color: var(--medical-500, #10b981); opacity: 0.1;"></div>
        <div class="absolute top-[20%] -right-[5%] w-[40%] h-[60%] rounded-full animate-float-orb-slow blur-[100px]"
            style="background-color: var(--medical-600, #059669); opacity: 0.08;"></div>
        <div class="absolute -bottom-[10%] left-[20%] w-[35%] h-[45%] rounded-full animate-float-orb-delayed blur-[130px]"
            style="background-color: var(--medical-500, #10b981); opacity: 0.05;"></div>
    </div>

    <!-- Toast Container for Admin Notifications -->
    <div id="toast-container"
        class="fixed top-24 right-6 z-[100] flex flex-col gap-3 w-full max-w-sm pointer-events-none"></div>

    <!-- Sidebar Overlay (Mobile) -->
    <div id="sidebarOverlay" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-40 hidden lg:hidden"></div>

    <!-- Sidebar -->
    <aside id="sidebar"
        class="fixed top-0 left-0 h-screen w-64 shadow-2xl z-50 transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out flex flex-col border-r border-white/5 dark:border-gray-800"
        style="background: var(--sidebar-bg, linear-gradient(180deg, #0f172a 0%, #020617 100%));">
        <!-- Sidebar Header -->
        <div class="h-20 flex items-center px-6 border-b border-white/5 bg-white/5 backdrop-blur-sm">
            <div class="flex items-center gap-3">
                <div
                    class="w-10 h-10 rounded-xl bg-gradient-to-br from-medical-500 to-medical-600 flex items-center justify-center shadow-lg ring-1 ring-white/10">
                    <i class="bi bi-heart-pulse-fill text-white text-lg"></i>
                </div>
                <div>
                    <h4 class="font-display font-bold text-white text-base leading-tight tracking-wide">
                        {{ config('app.name', 'Sistema Médico') }}</h4>
                    <p class="text-medical-500 text-[10px] uppercase tracking-wider font-bold mt-0.5">Administración</p>
                </div>
            </div>
        </div>

        <!-- Navigation Menu -->
        <nav
            class="flex-1 px-3 py-6 space-y-1 overflow-y-auto scrollbar-thin scrollbar-thumb-white/10 scrollbar-track-transparent">
            <!-- Dashboard -->
            <a href="{{ route('admin.dashboard') }}"
                class="flex items-center px-3 py-2.5 rounded-lg mb-4 transition-all duration-200 group {{ request()->is('*/admin/dashboard') ? 'bg-medical-500/20 text-medical-400 ring-1 ring-medical-500/30' : 'text-slate-400 hover:bg-white/5 hover:text-slate-200' }}">
                <i
                    class="bi bi-grid-1x2-fill text-lg mr-3 {{ request()->is('*/admin/dashboard') ? 'text-medical-400' : 'text-slate-500 group-hover:text-slate-200 transition-colors' }}"></i>
                <span class="font-medium text-sm">Dashboard</span>
            </a>

            <!-- Usuarios Section -->
            <div class="px-3 pb-2 pt-2">
                <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Gestión</p>
            </div>

            <div>
                <button onclick="toggleSubmenu('usuarios')"
                    class="w-full flex items-center justify-between px-3 py-2.5 rounded-lg text-slate-400 hover:bg-white/5 hover:text-slate-200 transition-all duration-200 group {{ request()->is('*/admin/administradores*') || request()->is('*/medicos*') || request()->is('*/pacientes*') || request()->is('*/representantes*') || request()->is('*/pacientes-especiales*') ? 'bg-white/5 text-slate-200' : '' }}">
                    <div class="flex items-center">
                        <i
                            class="bi bi-people-fill text-lg mr-3 {{ request()->is('*/admin/administradores*') || request()->is('*/medicos*') || request()->is('*/pacientes*') || request()->is('*/representantes*') || request()->is('*/pacientes-especiales*') ? 'text-medical-400' : 'text-slate-500 group-hover:text-slate-200' }}"></i>
                        <span class="font-medium text-sm">Usuarios</span>
                    </div>
                    <i class="bi bi-chevron-down text-xs opacity-50 transition-transform duration-200"
                        id="icon-usuarios"></i>
                </button>

                <div id="submenu-usuarios" class="ml-4 mt-1 pl-3 border-l border-white/10 space-y-1 hidden">
                    @if($admin && $admin->tipo_admin === 'Root')
                        <a href="{{ route('usuarios.index') }}"
                            class="flex items-center px-3 py-2 rounded-lg text-sm transition-all duration-200 {{ request()->is('*/usuarios*') ? 'text-medical-500 bg-medical-500/10' : 'text-slate-400 hover:text-slate-200' }}">
                            <span class="whitespace-nowrap group-[.collapsed]:hidden">Todos los Usuarios</span>
                        </a>
                    @endif
                    @if($admin && $admin->tipo_admin === 'Root')
                        <a href="{{ url('/admin/administradores') }}"
                            class="flex items-center px-3 py-2 rounded-lg text-sm transition-all duration-200 {{ request()->is('*/admin/administradores*') ? 'text-medical-500 bg-medical-500/10' : 'text-slate-400 hover:text-slate-200' }}">
                            <span class="whitespace-nowrap group-[.collapsed]:hidden">Administradores</span>
                        </a>
                    @endif
                    <a href="{{ route('medicos.index') }}"
                        class="flex items-center px-3 py-2 rounded-lg text-sm transition-all duration-200 {{ request()->is('*/medicos*') ? 'text-medical-500 bg-medical-500/10' : 'text-slate-400 hover:text-slate-200' }}">
                        <span class="whitespace-nowrap group-[.collapsed]:hidden">Médicos</span>
                    </a>
                    <a href="{{ route('pacientes.index') }}"
                        class="flex items-center px-3 py-2 rounded-lg text-sm transition-all duration-200 {{ request()->is('*/pacientes*') ? 'text-medical-500 bg-medical-500/10' : 'text-slate-400 hover:text-slate-200' }}">
                        <span class="whitespace-nowrap group-[.collapsed]:hidden">Pacientes</span>
                    </a>
                    <a href="{{ route('representantes.index') }}"
                        class="flex items-center px-3 py-2 rounded-lg text-sm transition-all duration-200 {{ request()->is('*/representantes*') ? 'text-medical-500 bg-medical-500/10' : 'text-slate-400 hover:text-slate-200' }}">
                        <span class="whitespace-nowrap group-[.collapsed]:hidden">Representantes</span>
                    </a>
                    <a href="{{ route('pacientes-especiales.index') }}"
                        class="flex items-center px-3 py-2 rounded-lg text-sm transition-all duration-200 {{ request()->is('*/pacientes-especiales*') ? 'text-medical-500 bg-medical-500/10' : 'text-slate-400 hover:text-slate-200' }}">
                        <span class="whitespace-nowrap group-[.collapsed]:hidden">Pacientes Especiales</span>
                    </a>
                </div>
            </div>

            <!-- Atención Médica Section -->
            <div class="px-3 pb-2 pt-4">
                <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Clínica</p>
            </div>

            <a href="{{ route('citas.index') }}"
                class="flex items-center px-3 py-2.5 rounded-lg transition-all duration-200 group {{ request()->is('*/citas*') ? 'bg-medical-500/20 text-medical-500 ring-1 ring-medical-500/30' : 'text-slate-400 hover:bg-white/5 hover:text-slate-200' }}">
                <i
                    class="bi bi-calendar-check-fill text-lg mr-3 {{ request()->is('*/citas*') ? 'text-medical-500' : 'text-slate-500 group-hover:text-slate-200' }}"></i>
                <span class="font-medium text-sm">Citas Médicas</span>
            </a>
            
            <!-- Infraestructura Section -->
            <div class="px-3 pb-2 pt-4">
                <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Recursos</p>
            </div>

            <a href="{{ route('especialidades.index') }}"
                class="flex items-center px-3 py-2.5 rounded-lg transition-all duration-200 group {{ request()->is('*/especialidades*') ? 'bg-medical-500/20 text-medical-500 ring-1 ring-medical-500/30' : 'text-slate-400 hover:bg-white/5 hover:text-slate-200' }}">
                <i
                    class="bi bi-bookmark-star-fill text-lg mr-3 {{ request()->is('*/especialidades*') ? 'text-medical-500' : 'text-slate-500 group-hover:text-slate-200' }}"></i>
                <span class="font-medium text-sm">Especialidades</span>
            </a>

            <a href="{{ route('consultorios.index') }}"
                class="flex items-center px-3 py-2.5 rounded-lg transition-all duration-200 group {{ request()->is('*/consultorios*') ? 'bg-medical-500/20 text-medical-500 ring-1 ring-medical-500/30' : 'text-slate-400 hover:bg-white/5 hover:text-slate-200' }}">
                <i
                    class="bi bi-building-fill text-lg mr-3 {{ request()->is('*/consultorios*') ? 'text-medical-500' : 'text-slate-500 group-hover:text-slate-200' }}"></i>
                <span class="font-medium text-sm">Consultorios</span>
            </a>

            <a href="{{ route('ubicacion.estados.index') }}"
                class="flex items-center px-3 py-2.5 rounded-lg transition-all duration-200 group {{ request()->is('*/ubicacion*') ? 'bg-medical-500/20 text-medical-500 ring-1 ring-medical-500/30' : 'text-slate-400 hover:bg-white/5 hover:text-slate-200' }}">
                <i
                    class="bi bi-geo-alt-fill text-lg mr-3 {{ request()->is('*/ubicacion*') ? 'text-medical-500' : 'text-slate-500 group-hover:text-slate-200' }}"></i>
                <span class="font-medium text-sm">Ubicación</span>
            </a>

            <!-- Sistema Section -->
            <div class="px-3 pb-2 pt-4">
                <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Sistema</p>
            </div>

            <a href="{{ route('admin.auditoria.index') }}"
                class="flex items-center px-3 py-2.5 rounded-lg mb-1 transition-all duration-200 group {{ request()->is('*/admin/auditoria*') ? 'bg-medical-500/20 text-medical-500 ring-1 ring-medical-500/30' : 'text-slate-400 hover:bg-white/5 hover:text-slate-200' }}">
                <i
                    class="bi bi-shield-lock-fill text-lg mr-3 {{ request()->is('*/admin/auditoria*') ? 'text-medical-500' : 'text-slate-500 group-hover:text-slate-200' }}"></i>
                <span class="font-medium text-sm">Auditoría (Seguridad)</span>
            </a>

            <a href="{{ route('facturacion.index') }}"
                class="flex items-center px-3 py-2.5 rounded-lg transition-all duration-200 group {{ request()->is('*/facturacion*') ? 'bg-medical-500/20 text-medical-500 ring-1 ring-medical-500/30' : 'text-slate-400 hover:bg-white/5 hover:text-slate-200' }}">
                <i
                    class="bi bi-receipt-cutoff text-lg mr-3 {{ request()->is('*/facturacion*') ? 'text-medical-500' : 'text-slate-500 group-hover:text-slate-200' }}"></i>
                <span class="font-medium text-sm">Facturación</span>
            </a>

            <a href="{{ route('pagos.index') }}"
                class="flex items-center px-3 py-2.5 rounded-lg transition-all duration-200 group {{ request()->is('*/pagos*') ? 'bg-medical-500/20 text-medical-500 ring-1 ring-medical-500/30' : 'text-slate-400 hover:bg-white/5 hover:text-slate-200' }}">
                <i
                    class="bi bi-credit-card-fill text-lg mr-3 {{ request()->is('*/pagos*') ? 'text-medical-500' : 'text-slate-500 group-hover:text-slate-200' }}"></i>
                <span class="font-medium text-sm">Pagos</span>
            </a>

            <a href="{{ route('admin.notificaciones.index') }}"
                class="flex items-center px-3 py-2.5 rounded-lg transition-all duration-200 group {{ request()->is('*/notificaciones*') || request()->is('admin/notificaciones*') ? 'bg-medical-500/20 text-medical-500 ring-1 ring-medical-500/30' : 'text-slate-400 hover:bg-white/5 hover:text-slate-200' }}">
                <i
                    class="bi bi-bell-fill text-lg mr-3 {{ request()->is('*/notificaciones*') || request()->is('admin/notificaciones*') ? 'text-medical-500' : 'text-slate-500 group-hover:text-slate-200' }}"></i>
                <span class="font-medium text-sm">Notificaciones</span>
            </a>

            @if($admin && $admin->tipo_admin === 'Root')
                <a href="{{ route('configuracion.index') }}"
                    class="flex items-center px-3 py-2.5 rounded-lg transition-all duration-200 group {{ request()->is('*/configuracion*') ? 'bg-medical-500/20 text-medical-500 ring-1 ring-medical-500/30' : 'text-slate-400 hover:bg-white/5 hover:text-slate-200' }}">
                    <i
                        class="bi bi-gear-fill text-lg mr-3 {{ request()->is('*/configuracion*') ? 'text-medical-500' : 'text-slate-500 group-hover:text-slate-200' }}"></i>
                    <span class="font-medium text-sm">Configuración</span>
                </a>
            @endif

            <!-- Reportes Section -->
            <div class="px-3 pb-2 pt-4">
                <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Análisis</p>
            </div>

            <a href="{{ route('reportes.index') }}"
                class="flex items-center px-3 py-2.5 rounded-lg transition-all duration-200 group {{ request()->is('*/reportes*') ? 'bg-medical-500/20 text-medical-500 ring-1 ring-medical-500/30' : 'text-slate-400 hover:bg-white/5 hover:text-slate-200' }}">
                <i
                    class="bi bi-graph-up-arrow text-lg mr-3 {{ request()->is('*/reportes*') ? 'text-medical-500' : 'text-slate-500 group-hover:text-slate-200' }}"></i>
                <span class="font-medium text-sm">Reportes y Estadísticas</span>
            </a>

            <div class="pb-20"></div>
        </nav>

        <!-- User Footer -->
        <div class="p-4 border-t border-white/5 bg-black/20">
            <div class="flex items-center gap-3">
                <div class="relative flex-shrink-0">
                    @if($admin && $admin->foto_perfil)
                        <img src="{{ asset('storage/' . $admin->foto_perfil) }}"
                            class="w-9 h-9 rounded-lg object-cover ring-2 ring-white/10 shadow-sm">
                    @else
                        <div
                            class="w-9 h-9 rounded-lg bg-medical-500 flex items-center justify-center text-xs font-bold text-white shadow-sm ring-2 ring-white/10">
                            {{ $admin ? strtoupper(substr($admin->primer_nombre, 0, 1)) : 'A' }}
                        </div>
                    @endif
                    <div
                        class="absolute -bottom-0.5 -right-0.5 w-2.5 h-2.5 bg-emerald-500 border-2 border-slate-900 rounded-full">
                    </div>
                </div>
                <div class="overflow-hidden flex-1">
                    <p class="text-xs font-semibold text-slate-200 truncate">
                        {{ $admin ? $admin->primer_nombre . ' ' . $admin->primer_apellido : 'Administrador' }}</p>
                    <p class="text-[10px] text-slate-500 flex items-center gap-1">
                        Admin • Online
                    </p>
                </div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="p-1.5 rounded-lg hover:bg-white/10 text-slate-400 hover:text-rose-400 transition-colors"
                        title="Cerrar Sesión">
                        <i class="bi bi-box-arrow-right"></i>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="lg:ml-64 min-h-screen">
        <!-- Top Bar -->
        <header
            class="sticky top-0 z-30 bg-white/80 dark:bg-gray-800/80 backdrop-blur-lg border-b border-gray-200 dark:border-gray-700 shadow-sm transition-colors duration-200">
            <div class="px-4 lg:px-6 h-16 flex items-center justify-between">
                <!-- Left: Mobile toggle & Title -->
                <div class="flex items-center gap-4">
                    <button id="sidebarToggle" class="lg:hidden p-2 rounded-lg hover:bg-gray-100 transition-colors">
                        <i class="bi bi-list text-2xl text-gray-700"></i>
                    </button>
                    <h1 class="text-lg lg:text-xl font-display font-bold text-gray-900 dark:text-white">
                        @yield('title', 'Dashboard')</h1>
                </div>

                <!-- Right: Dark Mode, Notifications & User -->
                <div class="flex items-center gap-3">
                    <!-- Dark Mode Toggle -->
                    <button id="darkModeToggle"
                        class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                        <i class="bi bi-moon-fill text-xl text-gray-700 dark:hidden dark:text-gray-300"></i>
                        <i class="bi bi-sun-fill text-xl text-gray-300 hidden dark:inline-block"></i>
                    </button>

                    <!-- Notifications -->
                    <div class="relative group">
                        <a href="{{ route('admin.notificaciones.index') }}"
                            class="relative flex h-11 w-11 items-center justify-center rounded-2xl bg-slate-100 text-slate-600 transition-all hover:bg-medical-50 hover:text-medical-600 ring-1 ring-slate-200 group-hover:ring-medical-500">
                            <i class="bi bi-bell-fill text-xl"></i>
                            @php
                                $unreadAdminCount = $admin ? $admin->unreadNotifications()->count() : 0;
                            @endphp
                            @if($unreadAdminCount > 0)
                                <span
                                    class="absolute -top-1 -right-1 flex h-5 w-5 items-center justify-center rounded-full bg-rose-500 text-[10px] font-bold text-white ring-2 ring-white"
                                    id="notif-count">
                                    {{ $unreadAdminCount > 9 ? '9+' : $unreadAdminCount }}
                                </span>
                            @else
                                <span
                                    class="absolute -top-1 -right-1 flex h-5 w-5 items-center justify-center rounded-full bg-rose-500 text-[10px] font-bold text-white ring-2 ring-white hidden"
                                    id="notif-count">0</span>
                            @endif
                        </a>

                        <!-- Notification Dropdown -->
                        <div
                            class="absolute right-0 top-full mt-2 w-80 scale-95 opacity-0 pointer-events-none group-hover:scale-100 group-hover:opacity-100 group-hover:pointer-events-auto transition-all duration-200 z-50">
                            <div class="bg-white rounded-2xl shadow-xl border border-slate-100 overflow-hidden">
                                <div
                                    class="flex items-center justify-between px-4 py-3 bg-medical-50 border-b border-medical-100">
                                    <h3 class="text-sm font-bold text-medical-800">Notificaciones</h3>
                                    @if($unreadAdminCount > 0)
                                        <form method="POST" action="{{ route('admin.notificaciones.leer-todas') }}"
                                            class="inline">
                                            @csrf
                                            <button type="submit"
                                                class="text-xs font-bold text-medical-600 hover:text-medical-700">
                                                Marcar todas como leídas
                                            </button>
                                        </form>
                                    @endif
                                </div>
                                <div class="max-h-96 overflow-y-auto">
                                    @forelse(($admin ? $admin->unreadNotifications()->take(5)->get() : collect()) as $notification)
                                        <a href="{{ $notification->data['link'] ?? '#' }}"
                                            class="block px-4 py-3 hover:bg-slate-50 transition-colors border-b border-slate-100 last:border-0"
                                            onclick="event.preventDefault(); marcarComoLeidaAdmin('{{ $notification->id }}', '{{ $notification->data['link'] ?? '#' }}')">
                                            <div class="flex items-start gap-3">
                                                <div
                                                    class="flex-shrink-0 h-10 w-10 rounded-full bg-{{ $notification->data['tipo'] === 'success' ? 'medical' : ($notification->data['tipo'] === 'warning' ? 'amber' : 'blue') }}-100 flex items-center justify-center">
                                                    <i
                                                        class="bi bi-{{ $notification->data['tipo'] === 'success' ? 'check-circle' : ($notification->data['tipo'] === 'warning' ? 'exclamation-triangle' : 'info-circle') }}-fill text-{{ $notification->data['tipo'] === 'success' ? 'medical' : ($notification->data['tipo'] === 'warning' ? 'amber' : 'blue') }}-600"></i>
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <p class="text-sm font-bold text-slate-800 truncate">
                                                        {{ $notification->data['titulo'] ?? 'Notificación' }}</p>
                                                    <p class="text-xs text-slate-600 mt-0.5 line-clamp-2">
                                                        {{ $notification->data['mensaje'] ?? '' }}</p>
                                                    <p class="text-xs text-slate-500 mt-0.5">
                                                        {{ $notification->created_at->diffForHumans() }}</p>
                                                </div>
                                            </div>
                                        </a>
                                    @empty
                                        <div class="px-4 py-8 text-center">
                                            <i class="bi bi-bell-slash text-4xl text-slate-300 mb-2"></i>
                                            <p class="text-sm text-slate-500">No tienes notificaciones nuevas</p>
                                        </div>
                                    @endforelse
                                </div>
                                @if($unreadAdminCount > 0)
                                    <div class="px-4 py-3 bg-slate-50 border-t border-slate-100">
                                        <a href="{{ route('notificaciones.index') }}"
                                            class="text-xs font-bold text-medical-600 hover:text-medical-700 flex items-center justify-center gap-1">
                                            Ver todas las notificaciones
                                            <i class="bi bi-arrow-right"></i>
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- User Dropdown -->
                    <div class="relative group">
                        <button class="flex items-center gap-3 p-2 rounded-lg hover:bg-gray-100 transition-colors">
                            @if($admin && $admin->foto_perfil)
                                <img src="{{ asset('storage/' . $admin->foto_perfil) }}"
                                    class="w-9 h-9 rounded-full object-cover shadow-sm ring-1 ring-gray-200">
                            @else
                                <div
                                    class="w-9 h-9 rounded-full bg-gradient-to-br from-medical-500 to-indigo-600 flex items-center justify-center text-white font-semibold shadow-md">
                                    {{ $admin ? strtoupper(substr($admin->primer_nombre, 0, 1)) : 'A' }}
                                </div>
                            @endif
                            <div class="hidden md:block text-left">
                                <p class="text-sm font-semibold text-gray-900 leading-tight">
                                    {{ $admin ? $admin->primer_nombre : 'Admin' }}</p>
                                <p class="text-[10px] text-gray-500 uppercase tracking-tighter">
                                    {{ $admin ? $admin->tipo_admin : 'User' }}</p>
                            </div>
                            <i
                                class="bi bi-chevron-down text-xs text-gray-400 group-hover:text-medical-500 transition-colors"></i>
                        </button>

                        <!-- Dropdown Menu -->
                        <div
                            class="absolute right-0 mt-2 w-60 bg-white rounded-2xl shadow-hard border border-gray-100 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 transform origin-top-right z-50 overflow-hidden">
                            <div class="p-4 bg-gradient-to-br from-gray-50 to-white border-b border-gray-100">
                                <p class="text-sm font-bold text-gray-900">
                                    {{ $admin ? $admin->primer_nombre . ' ' . $admin->primer_apellido : 'Administrador' }}
                                </p>
                                <p class="text-[10px] text-gray-500 truncate mt-0.5">{{ auth()->user()->correo }}</p>
                            </div>
                            <div class="p-2">
                                <a href="{{ route('admin.perfil.edit') }}"
                                    class="flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-medical-50 hover:text-medical-600 transition-all text-sm text-gray-600 font-medium">
                                    <i class="bi bi-person-badge text-lg"></i>
                                    <span>Mi Perfil Personalizado</span>
                                </a>
                                @if($admin && $admin->tipo_admin === 'Root')
                                    <a href="{{ route('configuracion.index') }}"
                                        class="flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-gray-50 transition-all text-sm text-gray-600 font-medium">
                                        <i class="bi bi-gear-wide-connected text-lg"></i>
                                        <span>Configuración Sistema</span>
                                    </a>
                                @endif
                            </div>
                            <div class="p-2 border-t border-gray-100">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                        class="w-full flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-rose-50 transition-colors text-sm text-rose-600">
                                        <i class="bi bi-box-arrow-right"></i>
                                        <span>Cerrar Sesión</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Content Area -->
        <div class="p-4 lg:p-6">
            <!-- Flash Messages -->
            @if(session('success'))
                <div
                    class="mb-4 p-4 bg-medical-50 border border-medical-200 rounded-2xl flex items-start gap-3 animate-slide-in-down shadow-sm">
                    <i class="bi bi-check-circle-fill text-medical-600 text-xl"></i>
                    <div class="flex-1">
                        <p class="text-sm font-bold text-gray-900">{{ session('success') }}</p>
                    </div>
                    <button onclick="this.parentElement.remove()"
                        class="text-medical-600 hover:text-medical-700 transition-colors">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>
            @endif

            @if(session('error'))
                <div
                    class="mb-4 p-4 bg-rose-50 border border-rose-200 rounded-2xl flex items-start gap-3 animate-slide-in-down shadow-sm">
                    <i class="bi bi-exclamation-triangle-fill text-rose-500 text-xl"></i>
                    <div class="flex-1">
                        <p class="text-sm font-bold text-gray-900">{{ session('error') }}</p>
                    </div>
                    <button onclick="this.parentElement.remove()" class="text-rose-600 hover:text-rose-700">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>
            @endif

            @if(session('warning'))
                <div
                    class="mb-4 p-4 bg-amber-50 border border-amber-200 rounded-2xl flex items-start gap-3 animate-slide-in-down shadow-sm">
                    <i class="bi bi-exclamation-circle-fill text-amber-500 text-xl"></i>
                    <div class="flex-1">
                        <p class="text-sm font-bold text-gray-900">{{ session('warning') }}</p>
                    </div>
                    <button onclick="this.parentElement.remove()" class="text-medical-600 hover:text-medical-700">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>
            @endif

            <!-- Main Content -->
            @yield('content')
        </div>
    </main>

    <script>
        // Sidebar Toggle for Mobile
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebar = document.getElementById('sidebar');
        const sidebarOverlay = document.getElementById('sidebarOverlay');

        if (sidebarToggle) {
            sidebarToggle.addEventListener('click', function () {
                sidebar.classList.toggle('-translate-x-full');
                sidebarOverlay.classList.toggle('hidden');
            });
        }

        if (sidebarOverlay) {
            sidebarOverlay.addEventListener('click', function () {
                sidebar.classList.add('-translate-x-full');
                sidebarOverlay.classList.add('hidden');
            });
        }



        // Toggle Submenu
        function toggleSubmenu(name) {
            const submenu = document.getElementById('submenu-' + name);
            const icon = document.getElementById('icon-' + name);

            if (submenu) {
                submenu.classList.toggle('hidden');
                if (icon) {
                    icon.classList.toggle('rotate-180');
                }
            }
        }

        // Auto-close alerts after 5 seconds
        document.addEventListener('DOMContentLoaded', function () {
            const alerts = document.querySelectorAll('.animate-slide-in-down');
            alerts.forEach(function (alert) {
                setTimeout(function () {
                    alert.style.opacity = '0';
                    setTimeout(() => alert.remove(), 300);
                }, 5000);
            });
        });

        // Auto-open active submenu
        document.addEventListener('DOMContentLoaded', function () {
            @if(request()->is('*/admin/administradores*') || request()->is('*/medicos*') || request()->is('*/pacientes*') || request()->is('*/representantes*') || request()->is('*/pacientes-especiales*'))
                        const usuariosSubmenu = document.getElementById('submenu-usuarios');
                const usuariosIcon = document.getElementById('icon-usuarios');
                if (usuariosSubmenu) usuariosSubmenu.classList.remove('hidden');
                if (usuariosIcon) usuariosIcon.classList.add('rotate-180');
            @endif
        });

        // Admin Toast Notification System
        function createToast(title, message, type = 'info', id = null, actions = null) {
            const container = document.getElementById('toast-container');
            if (!container) return;

            const toast = document.createElement('div');
            toast.className = `toast-card pointer-events-auto w-full backdrop-blur-xl rounded-2xl shadow-2xl p-4 flex flex-col gap-3 group border-t border-white/20`;

            const config = {
                success: { bg: 'bg-medical-500/90', text: 'text-white', icon: 'check-circle-fill', iconBg: 'bg-white/20' },
                warning: { bg: 'bg-amber-500/90', text: 'text-white', icon: 'exclamation-triangle-fill', iconBg: 'bg-white/20' },
                danger: { bg: 'bg-rose-500/90', text: 'text-white', icon: 'exclamation-circle-fill', iconBg: 'bg-white/20' },
                info: { bg: 'bg-blue-600/90', text: 'text-white', icon: 'info-circle-fill', iconBg: 'bg-white/20' }
            }[type] || { bg: 'bg-slate-700/90', text: 'text-white', icon: 'bell-fill', iconBg: 'bg-white/20' };

            toast.classList.add(...config.bg.split(' '));

            // Main content
            let htmlContent = `
                <div class="flex gap-4 items-start">
                    <div class="flex-shrink-0 h-10 w-10 rounded-xl ${config.iconBg} flex items-center justify-center text-white shadow-inner">
                        <i class="bi bi-${config.icon} text-xl"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h4 class="text-sm font-bold ${config.text} text-shadow-sm">${title}</h4>
                        <p class="text-xs ${config.text} opacity-90 mt-1 line-clamp-2">${message}</p>
                    </div>
                    <button class="text-white/60 hover:text-white transition-colors p-1" onclick="this.closest('.toast-card').remove()">
                        <i class="bi bi-x-lg text-xs"></i>
                    </button>
                </div>
            `;

            // Action buttons
            if (actions && actions.length > 0) {
                htmlContent += `<div class="flex gap-2 ml-14">`;
                actions.forEach(action => {
                    const btnClass = action.tipo === 'success' ? 'bg-white/20 hover:bg-white/30' :
                        action.tipo === 'secondary' ? 'bg-black/20 hover:bg-black/30' :
                            'bg-white/20 hover:bg-white/30';
                    const btnId = `action-${Math.random().toString(36).substr(2, 9)}`;

                    htmlContent += `
                        <button id="${btnId}" 
                                class="${btnClass} text-white text-xs font-bold px-3 py-1.5 rounded-lg transition-all flex items-center gap-1"
                                ${action.url ? `onclick="window.location.href='${action.url}'"` : ''}>
                            <i class="bi bi-${action.icono}"></i>
                            ${action.texto}
                        </button>
                    `;

                    // Attach action handler if needed
                    if (action.accion) {
                        setTimeout(() => {
                            document.getElementById(btnId)?.addEventListener('click', () => {
                                ejecutarAccionToast(action.accion, action.data, toast);
                            });
                        }, 100);
                    }
                });
                htmlContent += `</div>`;
            }

            toast.innerHTML = htmlContent;

            container.appendChild(toast);
            setTimeout(() => toast.classList.add('show'), 100);
            setTimeout(() => {
                toast.classList.remove('show');
                setTimeout(() => toast.remove(), 500);
            }, 12000); // 12 seconds for toasts with actions
        }

        // Execute Toast Action
        function ejecutarAccionToast(accion, data, toastElement) {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

            if (accion === 'confirmar-pago' && data.pago_id) {
                if (!confirm('¿Estás seguro de confirmar este pago?')) return;

                fetch(`/pagos/${data.pago_id}/confirmar`, {
                    method: 'PATCH',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                })
                    .then(response => response.json())
                    .then(result => {
                        if (result.success) {
                            toastElement.remove();
                            createToast('¡Éxito!', 'Pago confirmado correctamente', 'success');
                            setTimeout(() => window.location.reload(), 1500);
                        } else {
                            alert('Error: ' + (result.message || 'No se pudo confirmar el pago'));
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Error al procesar la acción');
                    });
            }
        }

        // Mark notification as read (Admin)
        function marcarComoLeidaAdmin(notificationId, redirectUrl) {
            fetch(`/admin/notificaciones/${notificationId}/marcar-leida`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success && redirectUrl && redirectUrl !== '#') {
                        window.location.href = redirectUrl;
                    } else {
                        window.location.reload();
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    if (redirectUrl && redirectUrl !== '#') {
                        window.location.href = redirectUrl;
                    }
                });
        }

        // Real-time Notifications Listener (Laravel Echo)
        document.addEventListener('DOMContentLoaded', () => {
            if (window.Echo && @json($admin !== null)) {
                const adminId = "{{ $admin->id ?? '' }}";
                if (adminId) {
                    window.Echo.private(`App.Models.Administrador.${adminId}`)
                        .notification((notification) => {
                            console.log('Real-time notification received:', notification);

                            // Handle potential nesting in .data
                            const data = notification.data || notification;

                            // Trigger toast
                            createToast(
                                data.titulo || 'Nueva Notificación',
                                data.mensaje || '',
                                data.tipo || 'info',
                                notification.id || data.id,
                                data.acciones || null
                            );

                            // Update bell badge
                            const badge = document.getElementById('notif-count');
                            if (badge) {
                                let currentCount = parseInt(badge.innerText) || 0;
                                badge.innerText = currentCount + 1;
                                badge.classList.remove('hidden');

                                // Simple bounce animation on badge
                                badge.style.transform = 'scale(1.4)';
                                setTimeout(() => badge.style.transform = 'scale(1)', 300);
                            }
                        })
                        .error((error) => {
                            console.error('WebSocket Error:', error);
                        });
                }
            } else {
                console.warn('Laravel Echo not found. Real-time notifications disabled.');
            }
        });

        // Show unread admin notifications as toasts ONLY if the login flag is present
        @if(session('mostrar_bienvenida_toasts') && $admin)
            @php
                $toasts = $admin->unreadNotifications->take(3)->map(function ($n) {
                    return [
                        'id' => $n->id,
                        'title' => $n->data['titulo'] ?? 'Notificación',
                        'message' => $n->data['mensaje'] ?? '',
                        'tipo' => $n->data['tipo'] ?? 'info',
                        'acciones' => $n->data['acciones'] ?? null
                    ];
                });
                session()->forget('mostrar_bienvenida_toasts');
            @endphp
            const unreadToasts = @json($toasts);
            unreadToasts.forEach((toast, index) => {
                setTimeout(() => {
                    createToast(toast.title, toast.message, toast.tipo, toast.id, toast.acciones);
                }, 500 * (index + 1));
                });
        @endif
</script>
    
@stack('scripts')
    
    <!-- Dark Mode Script -->
    <script>
        // Dark Mode Toggle Logic
        const darkModeToggle = document.getElementById('darkModeToggle');
const htmlElement = document.documentElement;
        
        // Check for saved preference or system preference
        const savedTheme = localStorage.getItem('theme');
const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
        
        if (savedTheme === 'dark' || (!savedTheme && prefersDark)) {
            htmlElement.classList.add('dark');
}
        
        // Toggle dark mode
        if (darkModeToggle) {
            darkModeToggle.addEventListener('click', () => {
                htmlElement.classList.toggle('dark');
                const isDark = htmlElement.classList.contains('dark');
                localStorage.setItem('theme', isDark ? 'dark' : 'light');
            });
        }
    </s
cript>
</body></html>
