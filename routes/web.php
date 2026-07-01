<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\AdministradorController;
use App\Http\Controllers\MedicoController;
use App\Http\Controllers\PacienteController;
use App\Http\Controllers\CitaController;
use App\Http\Controllers\EspecialidadController;
use App\Http\Controllers\ConsultorioController;
use App\Http\Controllers\UbicacionController;
use App\Http\Controllers\FacturacionController;
use App\Http\Controllers\PagoController;
use App\Http\Controllers\HistoriaClinicaController;
use App\Http\Controllers\OrdenMedicaController;
use App\Http\Controllers\NotificacionController;
use App\Http\Controllers\ConfiguracionController;
use App\Http\Controllers\RepresentanteController;
use App\Http\Controllers\PacienteEspecialController;
use App\Http\Controllers\Admin\AdminNotificationController;
use App\Http\Controllers\ReportController;

/*
|--------------------------------------------------------------------------
| Rutas Públicas
|--------------------------------------------------------------------------
*/

// Página principal
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Rutas de autenticación
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:10,1');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
// Rutas de recuperación profesional
Route::get('/recovery', [AuthController::class, 'showRecovery'])->name('recovery');
Route::post('/recovery/send-email', [AuthController::class, 'sendRecovery'])->name('recovery.send-email')->middleware('throttle:5,1');
Route::post('/recovery/get-questions', [AuthController::class, 'getSecurityQuestions'])->name('recovery.get-questions')->middleware('throttle:5,1');
Route::post('/recovery/verify-answers', [AuthController::class, 'verifySecurityAnswers'])->name('recovery.verify-answers')->middleware('throttle:5,1');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/password/reset', [AuthController::class, 'showRecovery'])->name('password.request');
Route::get('/reset-password/{token}', [AuthController::class, 'showResetPassword'])->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');

// AJAX Validation Routes for Register
// AJAX Validation Routes for Register (Legacy/Auth)
Route::post('/verificar-correo', [AuthController::class, 'verificarCorreo'])->name('auth.verificar-correo');
Route::post('/verificar-documento', [AuthController::class, 'getSecurityQuestions'])->name('auth.verificar-documento'); // Reuse getSecurityQuestions as it checks existence

// Rutas de validación AJAX para Registro (usadas por register.blade.php)
Route::post('/validate/email', [AuthController::class, 'checkEmail'])->name('validate.email');
Route::post('/validate/document', [AuthController::class, 'checkDocument'])->name('validate.document');

// Public Location Routes for Register (using closures to avoid middleware)
Route::get('ubicacion/get-ciudades/{estadoId}', function ($estadoId) {
    $ciudades = \App\Models\Ciudad::where('id_estado', $estadoId)->where('status', true)->get();
    return response()->json($ciudades);
});

Route::get('ubicacion/get-municipios/{estadoId}', function ($estadoId) {
    $municipios = \App\Models\Municipio::where('id_estado', $estadoId)->where('status', true)->get();
    return response()->json($municipios);
});

Route::get('ubicacion/get-parroquias/{municipioId}', function ($municipioId) {
    $parroquias = \App\Models\Parroquia::where('id_municipio', $municipioId)->where('status', true)->get();
    return response()->json($parroquias);
});

if (app()->environment('local')) {
    Route::middleware(['auth', 'role:admin'])->group(function () {
        Route::get('/force-reset-questions', function () {
            // ID 7 as seen in logs "milenasivira@gmail.com"
            $usuario = \App\Models\Usuario::find(7);

            if (!$usuario)
                return "Usuario ID 7 no encontrado";

            // Delet old answers
            \App\Models\RespuestaSeguridad::where('user_id', $usuario->id)->delete();

            // Create new ones (All 'hola')
            // Encrypted with md5(md5(trim('hola'))) -> 'cf96bce69f409820e4b6bce661eb4e78'
            $hash = md5(md5('hola'));

            // Pregunta IDs: 1, 2, 3 (Assuming they exist in catalog)
            for ($i = 1; $i <= 3; $i++) {
                \App\Models\RespuestaSeguridad::create([
                    'user_id' => $usuario->id,
                    'pregunta_id' => $i,
                    'respuesta_hash' => $hash
                ]);
            }

            return "Preguntas de seguridad reseteadas para {$usuario->correo}. La respuesta ahora es 'hola' para las 3 preguntas.";
        });

        Route::get('/test-user-search/{email}', function ($email) {
            $usuario = \App\Models\Usuario::where('correo', $email)->first();

            if ($usuario) {
                $respuestas = \App\Models\RespuestaSeguridad::where('user_id', $usuario->id)
                    ->with('pregunta')
                    ->get();

                return response()->json([
                    'found' => true,
                    'usuario' => [
                        'id' => $usuario->id,
                        'correo' => $usuario->correo,
                        'numero_documento' => $usuario->numero_documento ?? 'N/A',
                        'rol_id' => $usuario->rol_id
                    ],
                    'respuestas_count' => $respuestas->count(),
                    'preguntas' => $respuestas->map(fn($r) => [
                        'pregunta_id' => $r->pregunta_id,
                        'pregunta_texto' => $r->pregunta->pregunta ?? 'N/A'
                    ])
                ]);
            }

            return response()->json([
                'found' => false,
                'searched_email' => $email,
                'all_usuarios_count' => \App\Models\Usuario::count(),
                'sample_emails' => \App\Models\Usuario::limit(5)->pluck('correo')
            ]);
        })->name('test.user.search');

        Route::get('/fix-payment-methods', function () {
            $methods = \App\Models\MetodoPago::all();
            $count = 0;
            foreach ($methods as $method) {
                if (empty($method->nombre)) {
                    $method->nombre = $method->descripcion;
                    $method->save();
                    $count++;
                }
            }
            return "Se han corregido $count métodos de pago. Ya puede recargar la página de registro de pago.";
        });
    });
}

// Rutas públicas para búsqueda de médicos
Route::get('buscar-medicos-publico', [MedicoController::class, 'buscar'])->name('medicos.buscar.publico');

// =========================================================================
// RUTAS AJAX PARA SISTEMA DE CITAS (sin autenticación para AJAX del frontend)
// =========================================================================
Route::get('/ajax/verificar-correo', [AuthController::class, 'verificarCorreo'])->name('ajax.verificar-correo');

Route::prefix('ajax/citas')->group(function () {
    Route::get('/consultorios-por-estado/{estadoId}', [CitaController::class, 'getConsultoriosPorEstado']);
    Route::get('/especialidades-por-consultorio/{consultorioId}', [CitaController::class, 'getEspecialidadesPorConsultorio']);
    Route::get('/consultorios-por-especialidad/{especialidadId}', [CitaController::class, 'getConsultoriosPorEspecialidad']);
    Route::get('/medicos', [CitaController::class, 'getMedicosPorEspecialidadConsultorio']);
    Route::get('/horarios-disponibles', [CitaController::class, 'getHorariosDisponibles']);
    Route::get('/get-next-sequence/{numero_documento}', [CitaController::class, 'getNextSequence']);
    Route::get('/pacientes-especiales-por-representante/{representanteId}', [CitaController::class, 'getPacientesEspecialesPorRepresentante']);
    Route::get('/verificar-documento', [CitaController::class, 'verificarDocumento']);
});

/*
|--------------------------------------------------------------------------
| Rutas Protegidas (Requieren Autenticación)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    // =========================================================================
    // DASHBOARDS SEGÚN ROL
    // =========================================================================

    Route::get('/admin/dashboard', [AdministradorController::class, 'dashboard'])->name('admin.dashboard')->middleware('role:admin');
    Route::get('/medico/dashboard', [MedicoController::class, 'dashboard'])->name('medico.dashboard')->middleware('role:medico');
    Route::get('/medico/facturacion/{id}', [App\Http\Controllers\FacturacionController::class, 'misFacturasShow'])->name('medico.facturacion.show')->middleware('role:medico');
    Route::get('/medico/mi-horario', [MedicoController::class, 'miHorario'])->name('medico.horario.edit')->middleware('role:medico');
    Route::post('/medico/mi-horario/guardar', [MedicoController::class, 'miHorarioStore'])->name('medico.horario.update')->middleware('role:medico');
    Route::get('/medico/facturacion', [App\Http\Controllers\FacturacionController::class, 'misFacturas'])->name('medico.facturacion.index')->middleware('role:medico');
    Route::get('/paciente/dashboard', [PacienteController::class, 'dashboard'])->name('paciente.dashboard')->middleware('role:paciente');

    // Rutas de Notificaciones Admin
    Route::prefix('admin/notificaciones')->middleware('role:admin')->group(function () {
        Route::get('/', [AdminNotificationController::class, 'index'])->name('admin.notificaciones.index');
        Route::post('/{id}/marcar-leida', [AdminNotificationController::class, 'markAsRead'])->name('admin.notificaciones.marcar-leida');
        Route::post('/leer-todas', [AdminNotificationController::class, 'markAllAsRead'])->name('admin.notificaciones.leer-todas');
        Route::delete('/{id}', [AdminNotificationController::class, 'destroy'])->name('admin.notificaciones.destroy');
        Route::post('/eliminar-multiples', [AdminNotificationController::class, 'destroyAll'])->name('admin.notificaciones.destroy-all');
    });

    // Rutas de Broadcast Admin (Solo Root)
    Route::prefix('admin/broadcast')->middleware(['auth', 'role:admin'])->group(function () {
        Route::get('/create', [\App\Http\Controllers\Admin\AdminBroadcastController::class, 'create'])->name('admin.broadcast.create');
        Route::post('/store', [\App\Http\Controllers\Admin\AdminBroadcastController::class, 'store'])->name('admin.broadcast.store');
    });

    // =========================================================================
    // MÓDULO DE AUDITORÍA Y REPORTES
    // =========================================================================
    Route::prefix('admin/auditoria')->middleware('role:admin')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\AuditoriaController::class, 'index'])->name('admin.auditoria.index');
        Route::get('/citas', [\App\Http\Controllers\Admin\AuditoriaController::class, 'citas'])->name('admin.auditoria.citas');
        Route::get('/pagos', [\App\Http\Controllers\Admin\AuditoriaController::class, 'pagos'])->name('admin.auditoria.pagos');
        Route::get('/acceso-medico', [\App\Http\Controllers\Admin\AuditoriaController::class, 'accesoMedico'])->name('admin.auditoria.acceso-medico');
        Route::get('/auth-logs', [\App\Http\Controllers\Admin\AuditoriaController::class, 'authLogs'])->name('admin.auditoria.auth-logs');
        // Exportaciones Excel
        Route::get('/exportar/citas/excel', [\App\Http\Controllers\Admin\AuditoriaController::class, 'exportarCitasExcel'])->name('admin.auditoria.exportar.citas.excel');
        Route::get('/exportar/pagos/excel', [\App\Http\Controllers\Admin\AuditoriaController::class, 'exportarPagosExcel'])->name('admin.auditoria.exportar.pagos.excel');
        Route::get('/exportar/auth/excel', [\App\Http\Controllers\Admin\AuditoriaController::class, 'exportarAuthExcel'])->name('admin.auditoria.exportar.auth.excel');
        // Exportaciones PDF
        Route::get('/exportar/citas/pdf', [\App\Http\Controllers\Admin\AuditoriaController::class, 'exportarCitasPdf'])->name('admin.auditoria.exportar.citas.pdf');
        Route::get('/exportar/pagos/pdf', [\App\Http\Controllers\Admin\AuditoriaController::class, 'exportarPagosPdf'])->name('admin.auditoria.exportar.pagos.pdf');
    });

    // Rutas Específicas Paciente
    Route::prefix('paciente')->middleware(['auth', 'role:paciente'])->group(function () {
        Route::get('/historial', [PacienteController::class, 'historial'])->name('paciente.historial');
        Route::get('/pagos', [PacienteController::class, 'pagos'])->name('paciente.pagos');
        Route::get('/citas/create', [CitaController::class, 'create'])->name('paciente.citas.create');
        Route::post('/citas', [CitaController::class, 'store'])->name('paciente.citas.store');
        Route::get('/citas', [CitaController::class, 'index'])->name('paciente.citas.index');
        Route::get('/citas/{id}', [CitaController::class, 'show'])->name('paciente.citas.show');
        Route::get('/citas/{id}/comprobante', [CitaController::class, 'comprobante'])->name('paciente.citas.comprobante');

        // Rutas de pago del paciente
        Route::get('/pagos/registrar/{cita}', [PagoController::class, 'mostrarRegistroPago'])->name('paciente.pagos.registrar');
        Route::post('/pagos/registrar', [PagoController::class, 'registrarPagoPaciente'])->name('paciente.pagos.store');

        // Rutas de perfil del paciente
        Route::get('/perfil/editar', [PacienteController::class, 'editPerfil'])->name('paciente.perfil.edit');
        Route::put('/perfil', [PacienteController::class, 'updatePerfil'])->name('paciente.perfil.update');

        // Preguntas de Seguridad Paciente
        Route::get('/perfil/preguntas-seguridad', [PacienteController::class, 'showSecurityQuestions'])->name('paciente.security-questions');
        Route::post('/perfil/preguntas-seguridad', [PacienteController::class, 'updateSecurityQuestions'])->name('paciente.security-questions.update');

        // Rutas de notificaciones del paciente
        Route::prefix('notificaciones')->group(function () {
            Route::get('/', [\App\Http\Controllers\Paciente\PacienteNotificationController::class, 'index'])->name('paciente.notificaciones.index');
            Route::post('/{id}/marcar-leida', [\App\Http\Controllers\Paciente\PacienteNotificationController::class, 'markAsRead'])->name('paciente.notificaciones.marcar-leida');
            Route::post('/leer-todas', [\App\Http\Controllers\Paciente\PacienteNotificationController::class, 'markAllAsRead'])->name('paciente.notificaciones.leer-todas');
            Route::delete('/{id}', [\App\Http\Controllers\Paciente\PacienteNotificationController::class, 'destroy'])->name('paciente.notificaciones.destroy');
            Route::post('/eliminar-multiples', [\App\Http\Controllers\Paciente\PacienteNotificationController::class, 'destroyAll'])->name('paciente.notificaciones.destroy-all');
        });

        // Rutas de solicitudes de acceso a historial médico
        Route::get('/solicitudes-acceso', [HistoriaClinicaController::class, 'listarSolicitudesPaciente'])->name('paciente.solicitudes');
        Route::post('/solicitudes-acceso/{id}/aprobar', [HistoriaClinicaController::class, 'aprobarSolicitud'])->name('paciente.solicitudes.aprobar');
        Route::post('/solicitudes-acceso/{id}/rechazar', [HistoriaClinicaController::class, 'rechazarSolicitud'])->name('paciente.solicitudes.rechazar');

        // Rutas de Órdenes Médicas para Paciente
        Route::get('/ordenes', [OrdenMedicaController::class, 'indexPaciente'])->name('paciente.ordenes.index');
        Route::get('/ordenes/{id}', [OrdenMedicaController::class, 'showPaciente'])->name('paciente.ordenes.show');
        Route::get('/ordenes/solicitudes', [OrdenMedicaController::class, 'listarSolicitudesPaciente'])->name('paciente.ordenes.solicitudes');
        Route::post('/ordenes/solicitudes/{id}/aprobar', [OrdenMedicaController::class, 'aprobarSolicitudPaciente'])->name('paciente.ordenes.solicitudes.aprobar');
        Route::post('/ordenes/solicitudes/{id}/rechazar', [OrdenMedicaController::class, 'rechazarSolicitudPaciente'])->name('paciente.ordenes.solicitudes.rechazar');
    });

    // Rutas Específicas Representante
    Route::prefix('representante')->middleware(['auth', 'role:paciente'])->group(function () {
        Route::get('/solicitudes-acceso', [HistoriaClinicaController::class, 'listarSolicitudesRepresentante'])->name('representante.solicitudes');
        Route::post('/solicitudes-acceso/{id}/aprobar', [HistoriaClinicaController::class, 'aprobarSolicitud'])->name('representante.solicitudes.aprobar');
        Route::post('/solicitudes-acceso/{id}/rechazar', [HistoriaClinicaController::class, 'rechazarSolicitud'])->name('representante.solicitudes.rechazar');
    });

    // NOTE: Location AJAX routes are defined as public routes (lines 55-68) to allow access from registration page
    // Do NOT duplicate them here inside the auth middleware

    // =========================================================================
    // ADMINISTRACIÓN DEL SISTEMA
    // =========================================================================

    Route::prefix('admin')->middleware(['role:admin'])->group(function () {
        // Usuarios
        Route::resource('usuarios', UsuarioController::class);
        Route::post('usuarios/{id}/cambiar-password', [UsuarioController::class, 'cambiarPassword'])->name('usuarios.cambiar-password');

        // Administradores
        Route::resource('administradores', AdministradorController::class);
        Route::post('administradores/{id}/toggle-status', [AdministradorController::class, 'toggleStatus'])->name('administradores.toggle-status');

        // Ubicaciones (selects dependientes)
        Route::get('get-ciudades/{estadoId}', [AdministradorController::class, 'getCiudades'])->name('admin.get-ciudades');
        Route::get('get-municipios/{estadoId}', [AdministradorController::class, 'getMunicipios'])->name('admin.get-municipios');
        Route::get('get-parroquias/{municipioId}', [AdministradorController::class, 'getParroquias'])->name('admin.get-parroquias');

        // Perfil Admin
        Route::get('perfil/editar', [AdministradorController::class, 'editPerfil'])->name('admin.perfil.edit');
        Route::put('perfil', [AdministradorController::class, 'updatePerfil'])->name('admin.perfil.update');

        // Preguntas de Seguridad Admin
        Route::get('perfil/preguntas-seguridad', [AdministradorController::class, 'showSecurityQuestions'])->name('admin.security-questions');
        Route::post('perfil/preguntas-seguridad', [AdministradorController::class, 'updateSecurityQuestions'])->name('admin.security-questions.update');

        // =========================================================================
        // REPORTES Y ESTADÍSTICAS
        // =========================================================================
        Route::prefix('reportes')->name('reportes.')->group(function () {
            Route::get('/', [ReportController::class, 'index'])->name('index');
            Route::get('/operatividad', [ReportController::class, 'operatividad'])->name('operatividad');
            Route::get('/financiero', [ReportController::class, 'financiero'])->name('financiero');
            Route::get('/clinico', [ReportController::class, 'clinico'])->name('clinico');
            Route::get('/export/{type}', [ReportController::class, 'export'])->name('export');
        });
    });

    // =========================================================================
    // MÉDICOS
    // =========================================================================

    Route::resource('medicos', MedicoController::class)->middleware('role:admin');
    Route::get('medicos/{id}/horarios', [MedicoController::class, 'horarios'])->name('medicos.horarios')->middleware('role:admin');
    Route::post('medicos/{id}/guardar-horario', [MedicoController::class, 'guardarHorario'])->name('medicos.guardar-horario')->middleware('role:admin');
    Route::get('buscar-medicos', [MedicoController::class, 'buscar'])->name('medicos.buscar')->middleware('role:admin');

    Route::prefix('medico')->middleware(['auth', 'role:medico'])->group(function () {
        Route::get('/perfil/editar', [MedicoController::class, 'editPerfil'])->name('medico.perfil.edit');
        Route::put('/perfil', [MedicoController::class, 'updatePerfil'])->name('medico.perfil.update');

        // Preguntas de Seguridad Medico
        Route::get('/perfil/preguntas-seguridad', [MedicoController::class, 'showSecurityQuestions'])->name('medico.security-questions');
        Route::post('/perfil/preguntas-seguridad', [MedicoController::class, 'updateSecurityQuestions'])->name('medico.security-questions.update');

        // Métodos de Pago del Médico
        Route::get('/metodos-pago', [\App\Http\Controllers\DatoPagoMedicoController::class, 'index'])->name('medico.metodos-pago.index');
        Route::get('/metodos-pago/editar', [\App\Http\Controllers\DatoPagoMedicoController::class, 'edit'])->name('medico.metodos-pago.edit');
        Route::post('/metodos-pago', [\App\Http\Controllers\DatoPagoMedicoController::class, 'store'])->name('medico.metodos-pago.store');
        Route::post('/metodos-pago/status', [\App\Http\Controllers\DatoPagoMedicoController::class, 'updateStatus'])->name('medico.metodos-pago.update-status');

        // Rutas de Agenda y Fechas Indisponibles
        Route::get('/agenda', [MedicoController::class, 'agenda'])->name('medico.agenda');
        Route::post('/fecha-indisponible', [MedicoController::class, 'storeFechaIndisponible'])->name('medico.fecha-indisponible.store');
        Route::delete('/fecha-indisponible/{id}', [MedicoController::class, 'deleteFechaIndisponible'])->name('medico.fecha-indisponible.destroy');

        // Rutas de notificaciones del médico
        Route::prefix('notificaciones')->group(function () {
            Route::get('/', [\App\Http\Controllers\MedicoNotificacionController::class, 'index'])->name('medico.notificaciones.index');
            Route::get('/no-leidas', [\App\Http\Controllers\MedicoNotificacionController::class, 'getUnread'])->name('medico.notificaciones.unread');
            Route::post('/{id}/marcar-leida', [\App\Http\Controllers\MedicoNotificacionController::class, 'markAsRead'])->name('medico.notificaciones.mark-read');
            Route::post('/marcar-todas-leidas', [\App\Http\Controllers\MedicoNotificacionController::class, 'markAllAsRead'])->name('medico.notificaciones.mark-all-read');
        });
    });

    // =========================================================================
    // PACIENTES
    // =========================================================================

    Route::resource('pacientes', PacienteController::class)->middleware('role:admin|medico');
    Route::get('pacientes/{id}/historia-clinica', [PacienteController::class, 'historiaClinica'])->name('pacientes.historia-clinica')->middleware('role:admin|medico');
    Route::post('pacientes/{id}/actualizar-historia', [PacienteController::class, 'actualizarHistoriaClinica'])->name('pacientes.actualizar-historia')->middleware('role:admin|medico');

    // =========================================================================
    // CITAS MÉDICAS
    // =========================================================================

    Route::resource('citas', CitaController::class)->middleware('role:admin|medico');
    Route::post('citas/{id}/cambiar-estado', [CitaController::class, 'cambiarEstado'])->name('citas.cambiar-estado')->middleware('role:admin|medico');
    Route::post('citas/{id}/solicitar-cancelacion', [CitaController::class, 'solicitarCancelacion'])->name('citas.solicitar-cancelacion')->middleware('role:admin|medico|paciente');
    Route::get('buscar-disponibilidad', [CitaController::class, 'buscarDisponibilidad'])->name('citas.buscar-disponibilidad')->middleware('role:admin|medico');
    Route::get('events', [CitaController::class, 'events'])->name('citas.events')->middleware('role:admin|medico');
    Route::get('admin/buscar-paciente', [CitaController::class, 'buscarPaciente'])->name('admin.buscar-paciente')->middleware('role:admin');

    // =========================================================================
    // ESPECIALIDADES MÉDICAS
    // =========================================================================

    Route::resource('especialidades', EspecialidadController::class)->middleware('role:admin');
    Route::get('especialidades/{id}/medicos', [EspecialidadController::class, 'medicos'])->name('especialidades.medicos')->middleware('role:admin');

    // =========================================================================
    // CONSULTORIOS
    // =========================================================================

    Route::resource('consultorios', ConsultorioController::class)->middleware('role:admin');
    Route::get('consultorios/{id}/medicos', [ConsultorioController::class, 'medicos'])->name('consultorios.medicos')->middleware('role:admin');
    Route::get('consultorios/{id}/horarios', [ConsultorioController::class, 'horarios'])->name('consultorios.horarios')->middleware('role:admin');
    Route::get('get-ciudades-consultorio/{estadoId}', [ConsultorioController::class, 'getCiudades'])->name('consultorios.get-ciudades')->middleware('role:admin');
    Route::get('get-municipios-consultorio/{estadoId}', [ConsultorioController::class, 'getMunicipios'])->name('consultorios.get-municipios')->middleware('role:admin');
    Route::get('get-parroquias-consultorio/{municipioId}', [ConsultorioController::class, 'getParroquias'])->name('consultorios.get-parroquias')->middleware('role:admin');

    // =========================================================================
    // SISTEMA DE UBICACIÓN
    // =========================================================================

    Route::prefix('ubicacion')->middleware('role:admin')->group(function () {
        // Estados
        Route::get('estados', [UbicacionController::class, 'indexEstados'])->name('ubicacion.estados.index');
        Route::get('estados/create', [UbicacionController::class, 'createEstado'])->name('ubicacion.estados.create');
        Route::post('estados', [UbicacionController::class, 'storeEstado'])->name('ubicacion.estados.store');
        Route::get('estados/{id}/edit', [UbicacionController::class, 'editEstado'])->name('ubicacion.estados.edit');
        Route::put('estados/{id}', [UbicacionController::class, 'updateEstado'])->name('ubicacion.estados.update');
        Route::delete('estados/{id}', [UbicacionController::class, 'destroyEstado'])->name('ubicacion.estados.destroy');

        // Ciudades
        Route::get('ciudades', [UbicacionController::class, 'indexCiudades'])->name('ubicacion.ciudades.index');
        Route::get('ciudades/create', [UbicacionController::class, 'createCiudad'])->name('ubicacion.ciudades.create');
        Route::post('ciudades', [UbicacionController::class, 'storeCiudad'])->name('ubicacion.ciudades.store');
        Route::get('ciudades/{id}/edit', [UbicacionController::class, 'editCiudad'])->name('ubicacion.ciudades.edit');
        Route::put('ciudades/{id}', [UbicacionController::class, 'updateCiudad'])->name('ubicacion.ciudades.update');
        Route::delete('ciudades/{id}', [UbicacionController::class, 'destroyCiudad'])->name('ubicacion.ciudades.destroy');

        // Municipios
        Route::get('municipios', [UbicacionController::class, 'indexMunicipios'])->name('ubicacion.municipios.index');
        Route::get('municipios/create', [UbicacionController::class, 'createMunicipio'])->name('ubicacion.municipios.create');
        Route::post('municipios', [UbicacionController::class, 'storeMunicipio'])->name('ubicacion.municipios.store');
        Route::get('municipios/{id}/edit', [UbicacionController::class, 'editMunicipio'])->name('ubicacion.municipios.edit');
        Route::put('municipios/{id}', [UbicacionController::class, 'updateMunicipio'])->name('ubicacion.municipios.update');
        Route::delete('municipios/{id}', [UbicacionController::class, 'destroyMunicipio'])->name('ubicacion.municipios.destroy');

        // Parroquias
        Route::get('parroquias', [UbicacionController::class, 'indexParroquias'])->name('ubicacion.parroquias.index');
        Route::get('parroquias/create', [UbicacionController::class, 'createParroquia'])->name('ubicacion.parroquias.create');
        Route::post('parroquias', [UbicacionController::class, 'storeParroquia'])->name('ubicacion.parroquias.store');
        Route::get('parroquias/{id}/edit', [UbicacionController::class, 'editParroquia'])->name('ubicacion.parroquias.edit');
        Route::put('parroquias/{id}', [UbicacionController::class, 'updateParroquia'])->name('ubicacion.parroquias.update');
        Route::delete('parroquias/{id}', [UbicacionController::class, 'destroyParroquia'])->name('ubicacion.parroquias.destroy');
    });

    // =========================================================================
    // SISTEMA DE FACTURACIÓN
    // =========================================================================

    Route::resource('facturacion', FacturacionController::class);
    Route::post('facturacion/generar/{cita}', [FacturacionController::class, 'generarParaCita'])->name('facturacion.generar');
    Route::post('facturacion/{id}/enviar-recordatorio', [FacturacionController::class, 'enviarRecordatorio'])->name('facturacion.enviar-recordatorio');
    Route::get('facturacion/liquidaciones', [FacturacionController::class, 'resumenLiquidaciones'])->name('facturacion.liquidaciones');
    Route::post('facturacion/crear-liquidacion', [FacturacionController::class, 'crearLiquidacion'])->name('facturacion.crear-liquidacion');

    // =========================================================================
    // SISTEMA DE PAGOS
    // =========================================================================

    Route::resource('pagos', PagoController::class);
    Route::patch('pagos/{id}/confirmar', [PagoController::class, 'confirmarPago'])->name('pagos.confirmar');
    Route::patch('pagos/{id}/rechazar', [PagoController::class, 'rechazarPago'])->name('pagos.rechazar');
    Route::get('pagos/reporte', [PagoController::class, 'reportePagos'])->name('pagos.reporte');
    Route::get('mis-pagos', [PagoController::class, 'misPagos'])->name('pagos.mis-pagos');

    // =========================================================================
    // HISTORIA CLÍNICA
    // =========================================================================

    Route::prefix('historia-clinica')->group(function () {
        // Historia Clínica Base
        Route::get('base', [HistoriaClinicaController::class, 'indexBase'])->name('historia-clinica.base.index');
        Route::get('base/{pacienteId}', [HistoriaClinicaController::class, 'showBase'])->name('historia-clinica.base.show');
        Route::get('base/{pacienteId}/create', [HistoriaClinicaController::class, 'createBase'])->name('historia-clinica.base.create');
        Route::post('base/{pacienteId}', [HistoriaClinicaController::class, 'storeBase'])->name('historia-clinica.base.store');
        Route::get('base/{pacienteId}/edit', [HistoriaClinicaController::class, 'editBase'])->name('historia-clinica.base.edit');
        Route::put('base/{pacienteId}', [HistoriaClinicaController::class, 'updateBase'])->name('historia-clinica.base.update');

        // Evoluciones Clínicas
        Route::get('evoluciones-clinicas', [HistoriaClinicaController::class, 'indexGeneral'])->name('historia-clinica.evoluciones.general');
        Route::get('evoluciones/{pacienteId}', [HistoriaClinicaController::class, 'indexEvoluciones'])->name('historia-clinica.evoluciones.index');
        Route::get('evoluciones/cita/{citaId}/create', [HistoriaClinicaController::class, 'createEvolucion'])->name('historia-clinica.evoluciones.create');
        Route::post('evoluciones/cita/{citaId}', [HistoriaClinicaController::class, 'storeEvolucion'])->name('historia-clinica.evoluciones.store');
        Route::get('evoluciones/cita/{citaId}', [HistoriaClinicaController::class, 'showEvolucion'])->name('historia-clinica.evoluciones.show');
        Route::get('evoluciones/cita/{citaId}/edit', [HistoriaClinicaController::class, 'editEvolucion'])->name('historia-clinica.evoluciones.edit');
        Route::put('evoluciones/cita/{citaId}', [HistoriaClinicaController::class, 'updateEvolucion'])->name('historia-clinica.evoluciones.update');

        // Historial Completo
        Route::get('historial-completo/{pacienteId}', [HistoriaClinicaController::class, 'historialCompleto'])->name('historia-clinica.historial-completo');

        // Búsqueda
        Route::get('buscar/fecha/{pacienteId}', [HistoriaClinicaController::class, 'buscarPorFecha'])->name('historia-clinica.buscar.fecha');
        Route::get('buscar/diagnostico/{pacienteId}', [HistoriaClinicaController::class, 'buscarPorDiagnostico'])->name('historia-clinica.buscar.diagnostico');

        // Exportación
        Route::get('exportar/{pacienteId}', [HistoriaClinicaController::class, 'exportarHistorial'])->name('historia-clinica.exportar');
        Route::get('resumen/{pacienteId}', [HistoriaClinicaController::class, 'generarResumen'])->name('historia-clinica.resumen');

        // Permisos de acceso
        Route::post('solicitar-acceso/{pacienteId}', [HistoriaClinicaController::class, 'solicitarAcceso'])->name('historia-clinica.solicitar-acceso');
        Route::post('evolucion/{evolucionId}/solicitar-acceso', [HistoriaClinicaController::class, 'solicitarAccesoEvolucion'])->name('historia-clinica.solicitar-acceso-evolucion');
        Route::post('validar-token/{solicitudId}', [HistoriaClinicaController::class, 'validarTokenAcceso'])->name('historia-clinica.validar-token');
    });

    // =========================================================================
    // ÓRDENES MÉDICAS
    // =========================================================================

    Route::resource('ordenes-medicas', OrdenMedicaController::class);
    Route::get('ordenes-medicas/buscar', [OrdenMedicaController::class, 'buscar'])->name('ordenes-medicas.buscar');
    Route::get('ordenes-medicas/recetas', [OrdenMedicaController::class, 'recetas'])->name('ordenes-medicas.recetas');
    Route::get('ordenes-medicas/laboratorios', [OrdenMedicaController::class, 'laboratorios'])->name('ordenes-medicas.laboratorios');
    Route::get('ordenes-medicas/imagenologias', [OrdenMedicaController::class, 'imagenologias'])->name('ordenes-medicas.imagenologias');
    Route::get('ordenes-medicas/referencias', [OrdenMedicaController::class, 'referencias'])->name('ordenes-medicas.referencias');
    Route::get('ordenes-medicas/{id}/registrar-resultados', [OrdenMedicaController::class, 'registrarResultados'])->name('ordenes-medicas.registrar-resultados');
    Route::post('ordenes-medicas/{id}/guardar-resultados', [OrdenMedicaController::class, 'guardarResultados'])->name('ordenes-medicas.guardar-resultados');
    Route::get('ordenes-medicas/{id}/imprimir', [OrdenMedicaController::class, 'imprimir'])->name('ordenes-medicas.imprimir');
    Route::get('ordenes-medicas/exportar-periodo', [OrdenMedicaController::class, 'exportarPorPeriodo'])->name('ordenes-medicas.exportar-periodo');
    Route::get('ordenes-medicas/estadisticas', [OrdenMedicaController::class, 'estadisticas'])->name('ordenes-medicas.estadisticas');

    // Nuevas rutas de órdenes médicas
    Route::post('ordenes-medicas/{id}/solicitar-acceso', [OrdenMedicaController::class, 'solicitarAcceso'])->name('ordenes-medicas.solicitar-acceso');
    Route::post('ordenes-medicas/store-con-items', [OrdenMedicaController::class, 'storeConItems'])->name('ordenes-medicas.store-con-items');

    // =========================================================================
    // NOTIFICACIONES
    // =========================================================================

    Route::resource('notificaciones', NotificacionController::class);
    Route::post('notificaciones/{id}/reenviar', [NotificacionController::class, 'reenviar'])->name('notificaciones.reenviar');
    Route::post('notificaciones/masivo', [NotificacionController::class, 'enviarMasivo'])->name('notificaciones.masivo');
    Route::get('notificaciones/reporte', [NotificacionController::class, 'reporteNotificaciones'])->name('notificaciones.reporte');
    Route::get('notificaciones/estadisticas', [NotificacionController::class, 'estadisticas'])->name('notificaciones.estadisticas');
    Route::post('notificaciones/limpiar', [NotificacionController::class, 'limpiarNotificaciones'])->name('notificaciones.limpiar');

    // =========================================================================
    // CONFIGURACIÓN DEL SISTEMA
    // =========================================================================

    Route::prefix('configuracion')->middleware(['restrict.local.admin'])->group(function () {
        Route::get('/', [ConfiguracionController::class, 'index'])->name('configuracion.index');

        // General
        Route::get('general', [ConfiguracionController::class, 'general'])->name('configuracion.general');
        Route::put('general', [ConfiguracionController::class, 'actualizarGeneral'])->name('configuracion.general.actualizar');

        // Reparto
        Route::get('reparto', [ConfiguracionController::class, 'reparto'])->name('configuracion.reparto');
        Route::post('reparto', [ConfiguracionController::class, 'guardarReparto'])->name('configuracion.reparto.guardar');
        Route::put('reparto/{id}', [ConfiguracionController::class, 'actualizarReparto'])->name('configuracion.reparto.actualizar');
        Route::delete('reparto/{id}', [ConfiguracionController::class, 'eliminarReparto'])->name('configuracion.reparto.eliminar');

        // Tasas
        Route::get('tasas', [ConfiguracionController::class, 'tasas'])->name('configuracion.tasas');
        Route::post('tasas', [ConfiguracionController::class, 'guardarTasa'])->name('configuracion.tasas.guardar');
        Route::put('tasas/{id}', [ConfiguracionController::class, 'actualizarTasa'])->name('configuracion.tasas.actualizar');
        Route::get('tasas/sincronizar', [ConfiguracionController::class, 'sincronizarTasa'])->name('configuracion.tasas.sincronizar');
        Route::post('tasas/configuracion', [ConfiguracionController::class, 'actualizarConfiguracionTasa'])->name('configuracion.tasas.settings');
        Route::put('impuestos', [ConfiguracionController::class, 'actualizarImpuestos'])->name('configuracion.impuestos.actualizar');
        Route::delete('tasas/{id}', [ConfiguracionController::class, 'eliminarTasa'])->name('configuracion.tasas.eliminar');

        // Métodos de Pago
        Route::get('metodos-pago', [ConfiguracionController::class, 'metodosPago'])->name('configuracion.metodos-pago');
        Route::post('metodos-pago/bancarios', [ConfiguracionController::class, 'guardarDatosBancarios'])->name('configuracion.metodos-pago.bancarios');
        Route::post('metodos-pago', [ConfiguracionController::class, 'guardarMetodoPago'])->name('configuracion.metodos-pago.guardar');
        Route::put('metodos-pago/{id}', [ConfiguracionController::class, 'actualizarMetodoPago'])->name('configuracion.metodos-pago.actualizar');
        Route::delete('metodos-pago/{id}', [ConfiguracionController::class, 'eliminarMetodoPago'])->name('configuracion.metodos-pago.eliminar');

        // Correo
        Route::get('correo', [ConfiguracionController::class, 'correo'])->name('configuracion.correo');
        Route::put('correo', [ConfiguracionController::class, 'actualizarCorreo'])->name('configuracion.correo.actualizar');
        Route::post('correo/probar', [ConfiguracionController::class, 'probarCorreo'])->name('configuracion.correo.probar');

        // Mantenimiento
        Route::get('mantenimiento', [ConfiguracionController::class, 'mantenimiento'])->name('configuracion.mantenimiento');
        Route::post('mantenimiento/ejecutar', [ConfiguracionController::class, 'ejecutarMantenimiento'])->name('configuracion.mantenimiento.ejecutar');

        // Backup
        Route::get('backup', [ConfiguracionController::class, 'backup'])->name('configuracion.backup');
        Route::post('backup/generar', [ConfiguracionController::class, 'generarBackup'])->name('configuracion.backup.generar');

        // Estadísticas
        Route::get('estadisticas', [ConfiguracionController::class, 'estadisticas'])->name('configuracion.estadisticas');

        // Logs
        Route::get('logs', [ConfiguracionController::class, 'logs'])->name('configuracion.logs');
        Route::post('logs/limpiar', [ConfiguracionController::class, 'limpiarLogs'])->name('configuracion.logs.limpiar');

        // Servidor
        Route::get('servidor', [ConfiguracionController::class, 'servidor'])->name('configuracion.servidor');
    });

    // =========================================================================
    // REPRESENTANTES LEGALES
    // =========================================================================

    Route::resource('representantes', RepresentanteController::class);
    Route::post('representantes/{id}/asignar-paciente-especial', [RepresentanteController::class, 'asignarPacienteEspecial'])->name('representantes.asignar-paciente-especial');
    Route::delete('representantes/{id}/remover-paciente-especial/{pacienteEspecialId}', [RepresentanteController::class, 'removerPacienteEspecial'])->name('representantes.remover-paciente-especial');
    Route::put('representantes/{id}/actualizar-responsabilidad/{pacienteEspecialId}', [RepresentanteController::class, 'actualizarResponsabilidad'])->name('representantes.actualizar-responsabilidad');
    Route::get('representantes/buscar', [RepresentanteController::class, 'buscar'])->name('representantes.buscar');
    Route::get('representantes/reporte', [RepresentanteController::class, 'reporte'])->name('representantes.reporte');
    Route::get('representantes/estadisticas', [RepresentanteController::class, 'estadisticas'])->name('representantes.estadisticas');
    Route::get('representantes/exportar', [RepresentanteController::class, 'exportar'])->name('representantes.exportar');
    Route::get('representantes/importar', [RepresentanteController::class, 'importar'])->name('representantes.importar');
    Route::post('representantes/procesar-importacion', [RepresentanteController::class, 'procesarImportacion'])->name('representantes.procesar-importacion');
    Route::get('representantes/get-ciudades/{estadoId}', [RepresentanteController::class, 'getCiudades'])->name('representantes.get-ciudades');
    Route::get('representantes/get-municipios/{estadoId}', [RepresentanteController::class, 'getMunicipios'])->name('representantes.get-municipios');
    Route::get('representantes/get-parroquias/{municipioId}', [RepresentanteController::class, 'getParroquias'])->name('representantes.get-parroquias');

    // =========================================================================
    // PACIENTES ESPECIALES
    // =========================================================================

    Route::resource('pacientes-especiales', PacienteEspecialController::class);
    Route::post('pacientes-especiales/{id}/asignar-representante', [PacienteEspecialController::class, 'asignarRepresentante'])->name('pacientes-especiales.asignar-representante');
    Route::delete('pacientes-especiales/{id}/remover-representante/{representanteId}', [PacienteEspecialController::class, 'removerRepresentante'])->name('pacientes-especiales.remover-representante');
    Route::put('pacientes-especiales/{id}/actualizar-responsabilidad/{representanteId}', [PacienteEspecialController::class, 'actualizarResponsabilidad'])->name('pacientes-especiales.actualizar-responsabilidad');
    Route::get('pacientes-especiales/buscar', [PacienteEspecialController::class, 'buscar'])->name('pacientes-especiales.buscar');
    Route::get('pacientes-especiales/reporte', [PacienteEspecialController::class, 'reporte'])->name('pacientes-especiales.reporte');
    Route::get('pacientes-especiales/estadisticas', [PacienteEspecialController::class, 'estadisticas'])->name('pacientes-especiales.estadisticas');
    Route::get('pacientes-especiales/exportar', [PacienteEspecialController::class, 'exportar'])->name('pacientes-especiales.exportar');
    Route::get('pacientes-especiales/carnet/{id}', [PacienteEspecialController::class, 'carnet'])->name('pacientes-especiales.carnet');
    Route::get('pacientes-especiales/validar-necesidad/{pacienteId}', [PacienteEspecialController::class, 'validarNecesidadRepresentante'])->name('pacientes-especiales.validar-necesidad');
    Route::post('pacientes-especiales/registrar-automatico/{pacienteId}', [PacienteEspecialController::class, 'registrarAutomatico'])->name('pacientes-especiales.registrar-automatico');
});

/*
|--------------------------------------------------------------------------
| Rutas de Prueba y Desarrollo (Solo en entorno local)
|--------------------------------------------------------------------------
*/

if (app()->environment('local')) {
    Route::middleware(['auth', 'role:admin'])->group(function () {
        Route::get('/debug', function () {
            return view('debug');
        });

        Route::get('/test-mail', function () {
            try {
                \Illuminate\Support\Facades\Mail::raw('Este es un correo de prueba de Mailtrap desde el Sistema Médico.', function ($message) {
                    $message->to('test@example.com')
                        ->subject('Prueba de Configuración Mailtrap');
                });
                return 'Correo enviado correctamente. Revisa tu bandeja de entrada de Mailtrap.';
            } catch (\Exception $e) {
                return 'Error al enviar el correo: ' . $e->getMessage();
            }
        });
    });
}

/*
|--------------------------------------------------------------------------
| Rutas de Fallback (404)
|--------------------------------------------------------------------------
*/

Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});
