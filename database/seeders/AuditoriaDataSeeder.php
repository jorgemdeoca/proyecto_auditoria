<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

/**
 * AuditoriaDataSeeder
 * 
 * Genera datos históricos ricos para el módulo de Auditoría.
 * Crea pacientes, médicos adicionales, citas, pagos, historias clínicas,
 * evoluciones y órdenes médicas con fechas variadas en el pasado.
 * 
 * REVERTIR: Borrar manualmente paciente1@gmail.com, medico1@gmail.com, etc.
 * o ejecutar: DB::table('usuarios')->whereIn('correo', ['paciente1@...', ...])->delete();
 */
class AuditoriaDataSeeder extends Seeder
{
    // ─── Datos conocidos de la BD ───────────────────────────────────────────
    private int $adminUserId = 1;   // Usuario Root
    private int $adminId     = 1;   // Administrador.id del Root

    // Médicos existentes con sus especialidades y consultorios
    private array $medicos = [
        ['id' => 5, 'esp_id' => 1,  'cons_id' => 8, 'tarifa' => 50.00],
        ['id' => 6, 'esp_id' => 2,  'cons_id' => 7, 'tarifa' => 45.00],
        ['id' => 7, 'esp_id' => 5,  'cons_id' => 1, 'tarifa' => 60.00],
        ['id' => 8, 'esp_id' => 2,  'cons_id' => 1, 'tarifa' => 50.00],
    ];

    // Tasas de dólar existentes (id => valor)
    private array $tasas = [
        57 => 42.12, 58 => 42.56, 59 => 42.98,
        60 => 43.33, 61 => 43.48,
    ];

    // Métodos de pago disponibles
    private array $metodosIds = [1, 2, 3, 4];

    // Ubicación base Caracas
    private int $estadoId    = 1;
    private int $ciudadId    = 1;
    private int $municipioId = 1;
    private int $parroquiaId = 1;

    private array $nuevosPacientesIds = [];
    private int   $facturaNumBase     = 200;

    // ──────────────────────────────────────────────────────────────────────────

    public function run(): void
    {
        $this->command->info('🚀 Iniciando AuditoriaDataSeeder...');

        $maxFactura = DB::table('facturas_pacientes')->max('id') ?? 0;
        $this->facturaNumBase = $maxFactura + 154;

        $this->crearNuevosMedicos();
        $this->crearNuevosPacientes();
        $this->crearCitasHistoricas();
        $this->crearAuthLogsHistoricos();

        $this->command->info('✅ AuditoriaDataSeeder completado exitosamente!');
    }

    // =========================================================================
    // 1. MÉDICOS ADICIONALES
    // =========================================================================
    private function crearNuevosMedicos(): void
    {
        $this->command->info('   👨‍⚕️ Creando médicos adicionales...');

        $datos = [
            ['num' => 1, 'nombre' => 'Andrés',  'apellido' => 'Rodríguez', 'doc' => '8765432', 'esp_id' => 4,  'cons_id' => 3, 'tarifa' => 55.00, 'colegiatura' => 'MED-12845', 'meses' => 8],
            ['num' => 2, 'nombre' => 'Luisa',   'apellido' => 'Fernández', 'doc' => '9123456', 'esp_id' => 3,  'cons_id' => 5, 'tarifa' => 65.00, 'colegiatura' => 'MED-23981', 'meses' => 6],
            ['num' => 3, 'nombre' => 'Pedro',   'apellido' => 'Gutiérrez', 'doc' => '7654321', 'esp_id' => 10, 'cons_id' => 2, 'tarifa' => 70.00, 'colegiatura' => 'MED-34567', 'meses' => 4],
        ];

        foreach ($datos as $m) {
            $correo = 'medico' . $m['num'] . '@gmail.com';

            if (DB::table('usuarios')->where('correo', $correo)->exists()) {
                $userId   = DB::table('usuarios')->where('correo', $correo)->value('id');
                $medicoId = DB::table('medicos')->where('user_id', $userId)->value('id');
                if ($medicoId) {
                    $this->medicos[] = ['id' => $medicoId, 'esp_id' => $m['esp_id'], 'cons_id' => $m['cons_id'], 'tarifa' => $m['tarifa']];
                    $this->command->info("      ↩ Médico{$m['num']} ya existe (ID $medicoId)");
                    continue;
                }
            }

            $fecha = Carbon::now()->subMonths($m['meses'])->subDays(rand(1, 20));

            $userId = DB::table('usuarios')->insertGetId([
                'rol_id'            => 2,
                'correo'            => $correo,
                'password'          => md5(md5('@Barcelona2018')),
                'status'            => 1,
                'email_verified_at' => $fecha,
                'created_at'        => $fecha,
                'updated_at'        => $fecha,
            ]);

            $medicoId = DB::table('medicos')->insertGetId([
                'user_id'              => $userId,
                'primer_nombre'        => $m['nombre'],
                'segundo_nombre'       => '',
                'primer_apellido'      => $m['apellido'],
                'segundo_apellido'     => '',
                'tipo_documento'       => 'V',
                'numero_documento'     => $m['doc'],
                'fecha_nac'            => '1980-05-10',
                'estado_id'            => $this->estadoId,
                'ciudad_id'            => $this->ciudadId,
                'municipio_id'         => $this->municipioId,
                'parroquia_id'         => $this->parroquiaId,
                'direccion_detallada'  => 'Av. Principal, Caracas',
                'prefijo_tlf'          => '+58',
                'numero_tlf'           => '4' . rand(10, 29) . rand(1000000, 9999999),
                'genero'               => rand(0, 1) ? 'Masculino' : 'Femenino',
                'nro_colegiatura'      => $m['colegiatura'],
                'formacion_academica'  => 'Médico Cirujano - UCV',
                'experiencia_profesional' => rand(5, 20) . ' años de práctica clínica',
                'status'               => 1,
                'created_at'           => $fecha,
                'updated_at'           => $fecha,
            ]);

            DB::table('medico_especialidad')->insert([
                'medico_id' => $medicoId, 'especialidad_id' => $m['esp_id'],
                'tarifa' => $m['tarifa'], 'atiende_domicilio' => 0,
                'tarifa_extra_domicilio' => 0, 'anos_experiencia' => rand(5, 15),
                'status' => 1, 'created_at' => $fecha, 'updated_at' => $fecha,
            ]);

            DB::table('medico_consultorio')->insert([
                'medico_id'      => $medicoId,
                'consultorio_id' => $m['cons_id'],
                'dia_semana'     => ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes'][rand(0, 4)],
                'turno'          => rand(0, 1) ? 'mañana' : 'tarde',
                'horario_inicio' => rand(0, 1) ? '08:00:00' : '13:00:00',
                'horario_fin'    => rand(0, 1) ? '12:00:00' : '17:00:00',
                'status'         => 1,
                'created_at'     => $fecha,
                'updated_at'     => $fecha,
            ]);

            $this->medicos[] = ['id' => $medicoId, 'esp_id' => $m['esp_id'], 'cons_id' => $m['cons_id'], 'tarifa' => $m['tarifa']];

            $this->insertAuditLog('App\Models\Medico', $medicoId, 'created', $this->adminUserId, 'App\Models\Usuario',
                null, ['user_id' => $userId, 'primer_nombre' => $m['nombre'], 'primer_apellido' => $m['apellido']],
                $fecha, 'pagos');

            $this->command->info("      ✓ Médico{$m['num']}: Dr. {$m['nombre']} {$m['apellido']} (ID $medicoId)");
        }
    }

    // =========================================================================
    // 2. PACIENTES ADICIONALES
    // =========================================================================
    private function crearNuevosPacientes(): void
    {
        $this->command->info('   🧑 Creando pacientes adicionales...');

        $datos = [
            ['num' => 1, 'nombre' => 'María',     'apellido' => 'González',  'doc' => '12345601', 'meses' => 7],
            ['num' => 2, 'nombre' => 'Antonio',   'apellido' => 'Ramírez',   'doc' => '12345602', 'meses' => 6],
            ['num' => 3, 'nombre' => 'Valentina', 'apellido' => 'Torres',    'doc' => '12345603', 'meses' => 5],
            ['num' => 4, 'nombre' => 'Eduardo',   'apellido' => 'Martínez',  'doc' => '12345604', 'meses' => 4],
            ['num' => 5, 'nombre' => 'Sofía',     'apellido' => 'Herrera',   'doc' => '12345605', 'meses' => 3],
            ['num' => 6, 'nombre' => 'Miguel',    'apellido' => 'Castro',    'doc' => '12345606', 'meses' => 2],
            ['num' => 7, 'nombre' => 'Isabella',  'apellido' => 'Morales',   'doc' => '12345607', 'meses' => 1],
            ['num' => 8, 'nombre' => 'Diego',     'apellido' => 'Peñaloza',  'doc' => '12345608', 'meses' => 1],
        ];

        foreach ($datos as $p) {
            $correo = 'paciente' . $p['num'] . '@gmail.com';

            if (DB::table('usuarios')->where('correo', $correo)->exists()) {
                $userId     = DB::table('usuarios')->where('correo', $correo)->value('id');
                $pacienteId = DB::table('pacientes')->where('user_id', $userId)->value('id');
                if ($pacienteId) {
                    $this->nuevosPacientesIds[] = $pacienteId;
                    $this->command->info("      ↩ Paciente{$p['num']} ya existe (ID $pacienteId)");
                    continue;
                }
            }

            $fecha = Carbon::now()->subMonths($p['meses'])->subDays(rand(1, 15));

            $userId = DB::table('usuarios')->insertGetId([
                'rol_id'            => 3,
                'correo'            => $correo,
                'password'          => md5(md5('@Barcelona2018')),
                'status'            => 1,
                'email_verified_at' => $fecha,
                'created_at'        => $fecha,
                'updated_at'        => $fecha,
            ]);

            $genero = ($p['num'] % 2 === 0) ? 'Masculino' : 'Femenino';

            $pacienteId = DB::table('pacientes')->insertGetId([
                'user_id'             => $userId,
                'primer_nombre'       => $p['nombre'],
                'segundo_nombre'      => '',
                'primer_apellido'     => $p['apellido'],
                'segundo_apellido'    => '',
                'tipo_documento'      => 'V',
                'numero_documento'    => $p['doc'],
                'fecha_nac'           => date('Y-m-d', strtotime('-' . rand(25, 65) . ' years')),
                'estado_id'           => $this->estadoId,
                'ciudad_id'           => $this->ciudadId,
                'municipio_id'        => $this->municipioId,
                'parroquia_id'        => $this->parroquiaId,
                'direccion_detallada' => 'Urb. ' . ['Las Mercedes', 'Chacao', 'Altamira', 'Los Palos Grandes', 'El Paraíso'][rand(0, 4)] . ', Caracas',
                'prefijo_tlf'         => '+58',
                'numero_tlf'          => '4' . rand(10, 29) . rand(1000000, 9999999),
                'genero'              => $genero,
                'ocupacion'           => ['Docente', 'Ingeniero', 'Abogado', 'Comerciante', 'Estudiante', 'Empleado'][rand(0, 5)],
                'estado_civil'        => ['Soltero(a)', 'Casado(a)', 'Divorciado(a)'][rand(0, 2)],
                'es_especial'         => 0,
                'status'              => 1,
                'created_at'          => $fecha,
                'updated_at'          => $fecha,
            ]);

            $this->nuevosPacientesIds[] = $pacienteId;

            // AuditLog - Creación paciente
            $this->insertAuditLog('App\Models\Paciente', $pacienteId, 'created', $userId, 'App\Models\Usuario',
                null, ['primer_nombre' => $p['nombre'], 'primer_apellido' => $p['apellido'], 'numero_documento' => $p['doc']],
                $fecha, 'pacientes');

            // Auth log - Primer login
            $this->insertAuthLog($userId, $correo, 'LOGIN_OK', '181.55.' . rand(1, 200) . '.' . rand(1, 200), $fecha->copy()->addMinutes(10));

            $this->command->info("      ✓ Paciente{$p['num']}: {$p['nombre']} {$p['apellido']} (ID $pacienteId)");
        }
    }

    // =========================================================================
    // 3. CITAS + PAGOS + HISTORIAS
    // =========================================================================
    private function crearCitasHistoricas(): void
    {
        $this->command->info('   📅 Creando citas históricas con facturas, pagos e historias...');

        $todosLosPacientes = array_merge([1, 2, 3, 4], $this->nuevosPacientesIds);

        $motivosConsulta = [
            'Consulta de rutina y chequeo anual',
            'Dolor abdominal persistente',
            'Control de presión arterial',
            'Seguimiento de tratamiento médico',
            'Síntomas respiratorios agudos',
            'Control postoperatorio',
            'Cefalea frecuente e intensa',
            'Revisión de resultados de laboratorio',
            'Dolor articular crónico',
            'Seguimiento de diabetes mellitus',
            'Evaluación dermatológica',
            'Control cardiológico preventivo',
        ];

        $horasDisponibles = ['08:00:00', '09:00:00', '10:00:00', '11:00:00', '14:00:00', '15:00:00', '16:00:00'];

        $citasCreadas = 0;

        foreach ($this->medicos as $medico) {
            $medicoId = $medico['id'];
            $espId    = $medico['esp_id'];
            $consId   = $medico['cons_id'];
            $tarifa   = $medico['tarifa'];

            $numCitas = rand(5, 8);

            for ($i = 0; $i < $numCitas; $i++) {
                // Distribuir citas en los últimos 7 meses
                $mesesAtras = rand(1, 7);
                $diasAtras  = rand(1, 25);
                $fechaCita  = Carbon::now()->subMonths($mesesAtras)->subDays($diasAtras);
                $fechaCreacionCita = $fechaCita->copy()->subDays(rand(1, 7));

                $pacienteId = $todosLosPacientes[array_rand($todosLosPacientes)];
                $motivo     = $motivosConsulta[array_rand($motivosConsulta)];
                $hora       = $horasDisponibles[array_rand($horasDisponibles)];
                $tasaId     = array_keys($this->tasas)[array_rand(array_keys($this->tasas))];
                $tasaValor  = $this->tasas[$tasaId];

                // Estado cita: 70% completada, 15% cancelada, 15% No Asistió
                $rand = rand(1, 100);
                $estadoCita = $rand <= 70 ? 'Completada' : ($rand <= 85 ? 'Cancelada' : 'No Asistió');

                // Insertar cita
                $citaId = DB::table('citas')->insertGetId([
                    'paciente_id'      => $pacienteId,
                    'medico_id'        => $medicoId,
                    'especialidad_id'  => $espId,
                    'consultorio_id'   => $consId,
                    'fecha_cita'       => $fechaCita->format('Y-m-d'),
                    'hora_inicio'      => $hora,
                    'hora_fin'         => date('H:i:s', strtotime($hora) + 3600),
                    'duracion_minutos' => 60,
                    'tarifa'           => $tarifa,
                    'tarifa_extra'     => 0,
                    'tipo_consulta'    => 'Presencial',
                    'estado_cita'      => $estadoCita,
                    'motivo'           => $motivo,
                    'observaciones'    => null,
                    'status'           => 1,
                    'created_at'       => $fechaCreacionCita,
                    'updated_at'       => $fechaCita,
                ]);

                // AuditLog - Cita creada (estado inicial Programada)
                $this->insertAuditLog('App\Models\Cita', $citaId, 'created', $this->adminUserId, 'App\Models\Usuario',
                    null,
                    ['paciente_id' => $pacienteId, 'medico_id' => $medicoId, 'fecha_cita' => $fechaCita->format('Y-m-d'), 'estado_cita' => 'Programada', 'motivo' => $motivo],
                    $fechaCreacionCita, 'citas');

                // Cambios de estado según resultado
                if ($estadoCita === 'Cancelada') {
                    $this->insertAuditLog('App\Models\Cita', $citaId, 'state_changed', $this->adminUserId, 'App\Models\Usuario',
                        ['estado_cita' => 'Programada', 'confirmado_por' => null],
                        ['estado_cita' => 'Cancelada', 'motivo_cancelacion' => 'Paciente no se presentó a la cita'],
                        $fechaCita->copy()->addHours(2), 'citas');
                    $citasCreadas++;
                    continue; // Sin factura para canceladas
                }

                if ($estadoCita === 'No Asistió') {
                    $fechaOriginal = $fechaCita->copy()->subDays(4);
                    $this->insertAuditLog('App\Models\Cita', $citaId, 'updated', $this->adminUserId, 'App\Models\Usuario',
                        ['estado_cita' => 'Programada'],
                        ['estado_cita' => 'No Asistió'],
                        $fechaCita->copy()->addHours(3), 'citas');
                } else if ($estadoCita === 'Completada') {
                    $this->insertAuditLog('App\Models\Cita', $citaId, 'state_changed', $this->adminUserId, 'App\Models\Usuario',
                        ['estado_cita' => 'Programada'],
                        ['estado_cita' => 'Completada'],
                        $fechaCita->copy()->addHours(1), 'citas');
                }

                // ── Factura ───────────────────────────────────────────────────
                $montoUsd   = $tarifa;
                $montoBs    = round($montoUsd * $tasaValor, 2);
                $numFactura = 'FACT-' . $fechaCita->year . '-' . str_pad($this->facturaNumBase++, 6, '0', STR_PAD_LEFT);

                $facturaId = DB::table('facturas_pacientes')->insertGetId([
                    'cita_id'           => $citaId,
                    'paciente_id'       => $pacienteId,
                    'medico_id'         => $medicoId,
                    'monto_usd'         => $montoUsd,
                    'tasa_id'           => $tasaId,
                    'monto_bs'          => $montoBs,
                    'fecha_emision'     => $fechaCita->format('Y-m-d'),
                    'fecha_vencimiento' => null,
                    'numero_factura'    => $numFactura,
                    'status_factura'    => 'Emitida',
                    'status'            => 1,
                    'created_at'        => $fechaCita,
                    'updated_at'        => $fechaCita,
                ]);

                // ── Pago ──────────────────────────────────────────────────────
                $metodoPagoId = $this->metodosIds[array_rand($this->metodosIds)];
                $fechaPago    = $fechaCita->copy()->addDays(rand(0, 3));

                // Estados: 65% Confirmado, 20% Pendiente, 15% Rechazado
                $r = rand(1, 100);
                $estadoPago = $r <= 65 ? 'Confirmado' : ($r <= 85 ? 'Pendiente' : 'Rechazado');

                $referencia = 'REF-' . date('Ymd', $fechaPago->timestamp) . '-' . strtoupper(substr(md5(uniqid()), 0, 8));

                $pagoId = DB::table('pago')->insertGetId([
                    'id_factura_paciente'   => $facturaId,
                    'id_metodo'             => $metodoPagoId,
                    'fecha_pago'            => $fechaPago->format('Y-m-d'),
                    'monto_pagado_bs'       => $montoBs,
                    'monto_equivalente_usd' => $montoUsd,
                    'tasa_aplicada_id'      => $tasaId,
                    'referencia'            => $referencia,
                    'comentarios'           => 'Pago registrado via seeder de datos de auditoría.',
                    'comprobante'           => null,
                    'estado'                => $estadoPago,
                    'confirmado_por'        => ($estadoPago === 'Confirmado') ? $this->adminId : null,
                    'status'                => 1,
                    'created_at'            => $fechaPago,
                    'updated_at'            => $fechaPago,
                ]);

                // AuditLog - Creación pago (siempre como Pendiente al inicio)
                $this->insertAuditLog('App\Models\Pago', $pagoId, 'created', $this->adminUserId, 'App\Models\Usuario',
                    null,
                    ['id_factura_paciente' => $facturaId, 'id_metodo' => $metodoPagoId, 'monto_pagado_bs' => $montoBs, 'referencia' => $referencia, 'estado' => 'Pendiente'],
                    $fechaPago, 'pagos');

                if ($estadoPago === 'Confirmado') {
                    $fechaConf = $fechaPago->copy()->addHours(rand(1, 10));
                    DB::table('facturas_pacientes')->where('id', $facturaId)->update(['status_factura' => 'Pagada', 'updated_at' => $fechaConf]);
                    $this->insertAuditLog('App\Models\Pago', $pagoId, 'updated', $this->adminUserId, 'App\Models\Usuario',
                        ['estado' => 'Pendiente', 'confirmado_por' => null],
                        ['estado' => 'Confirmado', 'confirmado_por' => 'Administrador Root'],
                        $fechaConf, 'pagos');
                } elseif ($estadoPago === 'Rechazado') {
                    $fechaRec = $fechaPago->copy()->addHours(rand(2, 8));
                    $this->insertAuditLog('App\Models\Pago', $pagoId, 'updated', $this->adminUserId, 'App\Models\Usuario',
                        ['estado' => 'Pendiente'],
                        ['estado' => 'Rechazado', 'comentarios' => 'Referencia no verificable en el sistema bancario.'],
                        $fechaRec, 'pagos');
                }

                // ── Historia Clínica ──────────────────────────────────────────
                $this->crearOActualizarHistoria($pacienteId, $medicoId, $citaId, $fechaCita);

                $citasCreadas++;
            }
        }

        $this->command->info("      ✓ $citasCreadas citas procesadas");
    }

    // =========================================================================
    // 4. HISTORIA CLÍNICA + EVOLUCIÓN + ORDEN MÉDICA
    // =========================================================================
    private function crearOActualizarHistoria(int $pacienteId, int $medicoId, int $citaId, Carbon $fechaCita): void
    {
        $historiaExistente = DB::table('historia_clinica_base')->where('paciente_id', $pacienteId)->first();

        $tiposSangre = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];
        $alergias    = ['Ninguna conocida', 'Penicilina', 'Ibuprofeno', 'Mariscos', 'Polen', 'Látex'];
        $antecedentes = ['HTA (Hipertensión Arterial)', 'Diabetes Mellitus Tipo 2', 'Asma Bronquial', 'Artritis Reumatoide', 'Hipotiroidismo', 'Sin antecedentes relevantes'];
        $medicamentos = ['Losartán 50mg c/12h', 'Metformina 500mg c/8h, Omeprazol 20mg', 'Enalapril 10mg c/24h', 'Ninguno actualmente', 'Atorvastatina 40mg c/24h'];

        if (!$historiaExistente) {
            $historiaId = DB::table('historia_clinica_base')->insertGetId([
                'paciente_id'             => $pacienteId,
                'tipo_sangre'             => $tiposSangre[array_rand($tiposSangre)],
                'alergias'                => $alergias[array_rand($alergias)],
                'alergias_medicamentos'   => rand(0, 1) ? 'Ninguna' : 'Sulfas',
                'antecedentes_familiares' => $antecedentes[array_rand($antecedentes)] . ' (padre)',
                'antecedentes_personales' => $antecedentes[array_rand($antecedentes)],
                'enfermedades_cronicas'   => rand(0, 1) ? 'No refiere' : $antecedentes[rand(0, 3)],
                'medicamentos_actuales'   => $medicamentos[array_rand($medicamentos)],
                'cirugias_previas'        => rand(0, 1) ? 'Ninguna' : 'Apendicectomía (2015)',
                'habitos'                 => rand(0, 1) ? 'No fuma. No consume alcohol.' : 'Ex fumador (dejó hace 5 años).',
                'habito_tabaco'           => rand(0, 1) ? 'No' : 'Sí (10 cig/día)',
                'habito_alcohol'          => ['No', 'Ocasional', 'Moderado'][rand(0, 2)],
                'actividad_fisica'        => ['Sedentario', 'Caminata 3x/semana', 'Ejercicio regular'][rand(0, 2)],
                'dieta'                   => ['Sin restricciones', 'Hipocalórica', 'Diabética'][rand(0, 2)],
                'status'                  => 1,
                'created_at'              => $fechaCita->copy()->subMonths(rand(1, 3)),
                'updated_at'              => $fechaCita,
            ]);

            $this->insertAuditLog('App\Models\HistoriaClinicaBase', $historiaId, 'created', $medicoId, 'App\Models\Medico',
                null, ['paciente_id' => $pacienteId, 'tipo_sangre' => $tiposSangre[rand(0, 7)]],
                $fechaCita->copy()->subMonths(1), 'historia_clinica');
        } else {
            $historiaId = $historiaExistente->id;
            $medicNuevo = $medicamentos[array_rand($medicamentos)];
            $medicViejo = $historiaExistente->medicamentos_actuales ?? 'Ninguno';

            DB::table('historia_clinica_base')->where('id', $historiaId)->update([
                'medicamentos_actuales' => $medicNuevo, 'updated_at' => $fechaCita,
            ]);

            $this->insertAuditLog('App\Models\HistoriaClinicaBase', $historiaId, 'updated', $medicoId, 'App\Models\Medico',
                ['medicamentos_actuales' => $medicViejo],
                ['medicamentos_actuales' => $medicNuevo],
                $fechaCita, 'historia_clinica');
        }

        // ── Evolución Clínica ─────────────────────────────────────────────────
        $motivos = ['Control de HTA', 'Evaluación de malestar general', 'Seguimiento de diabetes', 'Control de colesterol', 'Consulta preventiva', 'Dolor torácico atípico'];
        $enfermedades = [
            'Paciente refiere mejoría del cuadro clínico. Niega fiebre. Cumple medicación indicada.',
            'Asintomático actualmente. Refiere leve malestar ocasional. Sin fiebre ni vómitos.',
            'Tos productiva de 3 días de evolución. Expectoración mucopurulenta. Afebril.',
            'Dolor en epigastrio de moderada intensidad. Sin irradiación. Sin fiebre.',
            'Paciente con control satisfactorio de cifras tensionales.',
        ];
        $examenes = [
            'T/A: 120/80 mmHg. FC: 72 lpm. FR: 18 rpm. Temp: 36.5°C. SpO2: 98%.',
            'T/A: 130/85 mmHg. FC: 80 lpm. Abdomen blando, depresible, sin dolor.',
            'Consciente, orientado. Pulmones: MV con sibilancias bilaterales. Corazón: RC rítmico.',
            'T/A: 118/75 mmHg. FC: 68 lpm. Sin adenopatías. Orofaringe eritematosa.',
        ];
        $planes = [
            'Continuar tratamiento. Control en 1 mes con nuevos exámenes.',
            'Ajustar dosis de medicamento. Laboratorios en 2 semanas.',
            'Solicitar Ecocardiograma. Indicar reposo y dieta blanda.',
            'Hidratación oral abundante. Antitérmico PRN. Control en 48h.',
        ];

        // Valores vitales aleatorios
        $peso = rand(55, 100);
        $talla = rand(155, 185);
        $imc   = round($peso / (($talla / 100) ** 2), 2);

        DB::table('evolucion_clinica')->insertGetId([
            'cita_id'               => $citaId,
            'paciente_id'           => $pacienteId,
            'medico_id'             => $medicoId,
            'peso_kg'               => $peso,
            'talla_cm'              => $talla,
            'imc'                   => $imc,
            'tension_sistolica'     => rand(110, 145),
            'tension_diastolica'    => rand(70, 95),
            'frecuencia_cardiaca'   => rand(60, 90),
            'temperatura_c'         => round(rand(365, 380) / 10, 1),
            'frecuencia_respiratoria' => rand(14, 22),
            'saturacion_oxigeno'    => rand(96, 99),
            'motivo_consulta'       => $motivos[array_rand($motivos)],
            'enfermedad_actual'     => $enfermedades[array_rand($enfermedades)],
            'examen_fisico'         => $examenes[array_rand($examenes)],
            'diagnostico'           => $antecedentes[array_rand($antecedentes)],
            'tratamiento'           => $planes[array_rand($planes)],
            'recomendaciones'       => 'Dieta balanceada. Actividad física moderada. Control periódico.',
            'notas_adicionales'     => rand(0, 1) ? null : 'Paciente colaborador durante la consulta.',
            'status'                => 1,
            'created_at'            => $fechaCita,
            'updated_at'            => $fechaCita,
        ]);

        // ── Orden Médica (50% de probabilidad) ───────────────────────────────
        if (rand(0, 1)) {
            $diagnosticos = ['Hipertensión Arterial Esencial', 'Diabetes Mellitus Tipo 2', 'Infección Respiratoria Aguda', 'Gastritis Crónica', 'Lumbalgia Mecánica', 'Ansiedad'];

            DB::table('ordenes_medicas')->insertGetId([
                'cita_id'              => $citaId,
                'paciente_id'          => $pacienteId,
                'medico_id'            => $medicoId,
                'tipo_orden'           => ['Receta', 'Laboratorio', 'Receta'][rand(0, 2)],
                'descripcion_detallada' => 'Paciente requiere medicación y seguimiento de exámenes de control.',
                'indicaciones'         => 'Tomar medicación con alimentos. Evitar exposición al sol.',
                'resultados'           => null,
                'fecha_emision'        => $fechaCita->format('Y-m-d'),
                'fecha_vigencia'       => $fechaCita->copy()->addDays(30)->format('Y-m-d'),
                'estado_orden'         => 'Emitida',
                'diagnostico_principal' => $diagnosticos[array_rand($diagnosticos)],
                'status'               => 1,
                'created_at'           => $fechaCita,
                'updated_at'           => $fechaCita,
            ]);
        }
    }

    // =========================================================================
    // 5. AUTH LOGS HISTÓRICOS
    // =========================================================================
    private function crearAuthLogsHistoricos(): void
    {
        $this->command->info('   🔐 Creando logs de autenticación históricos...');

        $usuarios = DB::table('usuarios')->get(['id', 'correo'])->toArray();
        $ips = ['181.55.120.14', '200.11.45.67', '190.203.55.90', '186.9.33.45', '212.77.88.99', '10.0.0.5', '192.168.1.100'];

        $total = 0;

        for ($mes = 6; $mes >= 0; $mes--) {
            $cantidad = rand(20, 45);

            for ($j = 0; $j < $cantidad; $j++) {
                $usuario = $usuarios[array_rand($usuarios)];
                $ip      = $ips[array_rand($ips)];
                $fecha   = Carbon::now()->subMonths($mes)->subDays(rand(0, 27))->subHours(rand(0, 23))->subMinutes(rand(0, 59));

                // Distribución realista de eventos
                $r = rand(1, 100);
                $event = match (true) {
                    $r <= 72 => 'LOGIN_OK',
                    $r <= 87 => 'LOGIN_FAIL',
                    $r <= 94 => 'LOGOUT',
                    $r <= 98 => 'LOCKOUT',
                    default  => 'UNLOCK',
                };

                DB::table('auth_logs')->insert([
                    'user_id'          => $usuario->id,
                    'correo_intentado' => $usuario->correo,
                    'event_type'       => $event,
                    'ip_address'       => $ip,
                    'user_agent'       => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) Chrome/125.0.0.0',
                    'session_id'       => null,
                    'created_at'       => $fecha,
                ]);
                $total++;
            }
        }

        $this->command->info("      ✓ $total registros de autenticación históricos creados");
    }

    // =========================================================================
    // HELPERS PRIVADOS
    // =========================================================================

    private function insertAuditLog(
        string  $auditableType,
        int     $auditableId,
        string  $event,
        int     $causerId,
        string  $causerType, // Unused but kept for signature compatibility
        ?array  $oldValues,
        ?array  $newValues,
        Carbon  $fecha,
        string  $modulo
    ): void {
        // Fetch causer nombre if not root
        $causerNombre = 'Administrador Root';
        
        DB::table('audit_logs')->insert([
            'auditable_type' => $auditableType,
            'auditable_id'   => $auditableId,
            'causer_id'      => $causerId,
            'causer_nombre'  => $causerNombre,
            'event'          => $event,
            'modulo'         => $modulo,
            'old_values'     => $oldValues ? json_encode($oldValues) : null,
            'new_values'     => $newValues ? json_encode($newValues) : null,
            'motivo'         => null,
            'ip_address'     => '127.0.0.1',
            'session_id'     => null,
            'created_at'     => $fecha,
        ]);
    }

    private function insertAuthLog(int $userId, string $correo, string $event, string $ip, Carbon $fecha): void
    {
        DB::table('auth_logs')->insert([
            'user_id'          => $userId,
            'correo_intentado' => $correo,
            'event_type'       => $event,
            'ip_address'       => $ip,
            'user_agent'       => 'Mozilla/5.0 AuditoriaSeeder/Registration',
            'session_id'       => null,
            'created_at'       => $fecha,
        ]);
    }
}
