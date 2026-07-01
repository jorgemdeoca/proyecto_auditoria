<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use App\Models\PreguntaCatalogo;
use App\Models\RespuestaSeguridad;
use App\Models\HistorialPassword;
use App\Models\Role;
use App\Models\Medico;
use App\Models\Paciente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\KnownDevice;
use App\Notifications\NewDeviceLoginNotification;
use App\Notifications\PasswordChangedNotification;
use App\Notifications\AccountLockedNotification;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'correo' => 'required|email|max:150',
            'password' => 'required|min:8'
        ], [
            'correo.required' => 'El correo electrónico es obligatorio',
            'correo.email' => 'Debe ingresar un correo electrónico válido',
            'password.required' => 'La contraseña es obligatoria',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Buscar usuario por correo primero
        $usuario = Usuario::where('correo', $request->correo)->first();

        if (!$usuario) {
            return redirect()->back()
                ->withErrors(['correo' => 'Las credenciales no coinciden con nuestros registros.'])
                ->withInput();
        }

        // Verificar status del usuario
        if (!$usuario->status && $usuario->status !== 2) {
            return redirect()->back()
                ->withErrors(['correo' => 'Esta cuenta está inactiva.'])
                ->withInput();
        }

        // Check if account is locked
        if ($usuario->status == 2) {
            // Check if lockout period has expired
            if ($usuario->blocked_until && $usuario->blocked_until <= now()) {
                // Auto-unlock account
                $usuario->status = 1;
                $usuario->blocked_until = null;
                $usuario->lock_reason = null;
                $usuario->save();
                
                Log::info('Account auto-unlocked after lockout period', [
                    'user_id' => $usuario->id,
                    'email' => $usuario->correo
                ]);
            } else {
                // Still locked
                $blockedUntil = $usuario->blocked_until ? $usuario->blocked_until->format('d/m/Y H:i') : 'indefinidamente';
                return redirect()->back()
                    ->withErrors(['correo' => "Tu cuenta está bloqueada hasta {$blockedUntil} por seguridad. {$usuario->lock_reason}"])
                    ->withInput();
            }
        }

        // Aplicar MD5 dos veces a la contraseña provista
        $passwordHash = md5(md5($request->password));

        // Verificar contraseña
        if ($usuario->password !== $passwordHash) {
            // Rate limiting por intentos fallidos usando BD (persistente)
            $usuario->increment('failed_login_count');
            $usuario->update(['last_failed_at' => now()]);

            $attempts = $usuario->failed_login_count;

            // Bloquear cuenta tras 5 intentos fallidos
            if ($attempts >= 5) {
                $blockedUntil = now()->addMinutes(15);
                $usuario->update([
                    'status' => 2,
                    'blocked_until' => $blockedUntil,
                    'lock_reason' => 'Múltiples intentos fallidos de inicio de sesión'
                ]);

                // Enviar notificación de bloqueo
                try {
                    $usuario->notify(new AccountLockedNotification([
                       'ip' => $request->ip(),
                       'time' => now()->format('d/m/Y H:i A'),
                       'unlock_time' => $blockedUntil->format('d/m/Y H:i A')
                    ]));
                } catch (\Exception $e) {
                    Log::error('Error enviando notificación de bloqueo: ' . $e->getMessage());
                }

                Log::warning('Account locked due to failed login attempts', [
                    'user_id' => $usuario->id,
                    'email' => $usuario->correo,
                    'ip' => $request->ip()
                ]);

                return redirect()->back()
                    ->withErrors(['correo' => "Cuenta bloqueada por seguridad hasta las {$blockedUntil->format('H:i')}. Demasiados intentos fallidos."])
                    ->withInput();
            }

            $remaining = 5 - $attempts;
            return redirect()->back()
                ->withErrors(['password' => "Contraseña inválida. {$remaining} intento(s) restante(s)."])
                ->withInput();
        }

        // Limpiar intentos fallidos en login exitoso
        $usuario->update(['failed_login_count' => 0, 'last_failed_at' => null]);

        // VALIDACIÓN DE ACCESO POR PORTAL CORRECTO
        $mapaRoles = [
            'admin' => 1,
            'medico' => 2,
            'paciente' => 3
        ];
        
        $rolSolicitado = $request->input('rol');

        if ($rolSolicitado && isset($mapaRoles[$rolSolicitado])) {
            $rolIdSolicitado = $mapaRoles[$rolSolicitado];

            if ($usuario->rol_id !== $rolIdSolicitado) {
                $rutaCorrecta = 'login';
                $nombreRolReal = '';
                $portalIntentado = '';

                switch ($usuario->rol_id) {
                    case 1: $rutaCorrecta = route('login', ['rol' => 'admin']); $nombreRolReal = 'Administrador'; break;
                    case 2: $rutaCorrecta = route('login', ['rol' => 'medico']); $nombreRolReal = 'Médico'; break;
                    case 3: $rutaCorrecta = route('login', ['rol' => 'paciente']); $nombreRolReal = 'Paciente'; break;
                }

                switch ($rolSolicitado) {
                    case 'admin': $portalIntentado = 'Administradores'; break;
                    case 'medico': $portalIntentado = 'Médicos'; break;
                    case 'paciente': $portalIntentado = 'Pacientes'; break;
                }

                return redirect($rutaCorrecta)
                    ->with('error', "Usted es un $nombreRolReal y no puede iniciar sesión desde el portal de $portalIntentado. Por favor ingrese sus credenciales aquí.");
            }
        }

        // Verificar estado del perfil específico
        $perfilInactivo = false;
        switch ($usuario->rol_id) {
            case 1: if ($usuario->administrador && !$usuario->administrador->status) $perfilInactivo = true; break;
            case 2: if ($usuario->medico && !$usuario->medico->status) $perfilInactivo = true; break;
            case 3: if ($usuario->paciente && !$usuario->paciente->status) $perfilInactivo = true; break;
        }

        if ($perfilInactivo) {
            return redirect()->back()->with('error', 'Su perfil de usuario ha sido desactivado.')->withInput();
        }

        // Iniciar sesión usando el guard web explícitamente (remember-me opcional)
        $remember = $request->boolean('remember', false);
        Auth::guard('web')->login($usuario, $remember);

        // Flag para mostrar toasts solo al iniciar sesión
        session(['mostrar_bienvenida_toasts' => true]);

        // --- VALIDACIÓN DE DISPOSITIVO CONOCIDO ---
        try {
            $ip = $request->ip();
            $userAgent = $request->header('User-Agent');
            
            $knownDevice = KnownDevice::where('user_id', $usuario->id)
                ->where('ip_address', $ip)
                ->where('user_agent', $userAgent)
                ->first();

            if ($knownDevice) {
                $knownDevice->update(['last_login_at' => now()]);
            } else {
                // Nuevo dispositivo detectado
                KnownDevice::create([
                    'user_id' => $usuario->id,
                    'ip_address' => $ip,
                    'user_agent' => $userAgent,
                    'last_login_at' => now()
                ]);

                // Notificar nuevo inicio de sesión
                $usuario->notify(new NewDeviceLoginNotification([
                    'ip' => $ip,
                    'device' => $userAgent,
                    'time' => now()->format('d/m/Y H:i:s')
                ]));
            }
        } catch (\Exception $e) {
            Log::error('Error en verificación de dispositivo conocido: ' . $e->getMessage());
        }

        // Redirigir según el rol
        return $this->redirectByRole($usuario);
    }

    public function showRegister()
    {
        $preguntas = PreguntaCatalogo::where('status', true)->get();
        $roles = Role::whereIn('id', [2, 3])->where('status', true)->get();
        $estados = \App\Models\Estado::where('status', true)->get();
        return view('auth.register', compact('preguntas', 'roles', 'estados'));
    }

    public function register(Request $request)
    {
        Log::info('Iniciando registro debugging', $request->all());

        // Limpiar campos opcionales vacíos para evitar problemas de validación
        if (empty($request->segundo_nombre)) {
            $request->request->remove('segundo_nombre');
        }
        if (empty($request->segundo_apellido)) {
            $request->request->remove('segundo_apellido');
        }

        $rules = [
            'rol_id' => 'required|in:2,3',
            'primer_nombre' => 'required|max:20|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s]+$/',
            'segundo_nombre' => 'sometimes|nullable|max:20|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s]+$/',
            'primer_apellido' => 'required|max:20|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s]+$/',
            'segundo_apellido' => 'sometimes|nullable|max:20|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s]+$/',
            'correo' => 'required|email|unique:usuarios,correo|max:150',
        ];

        Log::info('Debug rules applied:', $rules);

        $validator = Validator::make($request->all(), $rules, [
            'password' => [
                'required',
                'min:8',
                'confirmed',
                'regex:/[A-Z]/',
                'regex:/[0-9]/',
                'regex:/[@$!%*#?&.]/'
            ],
            'pregunta_seguridad_1' => 'required|exists:preguntas_catalogo,id',
            'pregunta_seguridad_2' => 'required|exists:preguntas_catalogo,id|different:pregunta_seguridad_1',
            'pregunta_seguridad_3' => 'required|exists:preguntas_catalogo,id|different:pregunta_seguridad_1|different:pregunta_seguridad_2',
            'respuesta_seguridad_1' => 'required|min:2',
            'respuesta_seguridad_2' => 'required|min:2',
            'respuesta_seguridad_3' => 'required|min:2',
            'tipo_documento' => 'required|in:V,E,P,J',
            'numero_documento' => [
                'required',
                function ($attribute, $value, $fail) use ($request) {
                    $tipo = $request->input('tipo_documento');
                    if ($tipo === 'V') {
                        if (!ctype_digit($value))
                            $fail('La cédula V debe contener solo números.');
                        elseif ($value <= 100000 || $value > 50000000)
                            $fail('La cédula V debe estar entre 100,000 y 50,000,000.');
                    } elseif ($tipo === 'E') {
                        if (!ctype_digit($value))
                            $fail('La cédula E debe contener solo números.');
                        elseif ($value <= 50000000 || $value > 100000000)
                            $fail('La cédula E debe estar entre 50,000,001 y 100,000,000.');
                    } elseif ($tipo === 'P') {
                        if (!ctype_alnum($value))
                            $fail('El pasaporte debe ser alfanumérico.');
                        elseif (strlen($value) < 8 || strlen($value) > 9)
                            $fail('El pasaporte debe tener entre 8 y 9 caracteres.');
                    } elseif ($tipo === 'J') {
                        if (!ctype_digit($value))
                            $fail('El RIF J debe contener solo números.');
                        elseif (strlen($value) < 8 || strlen($value) > 9)
                            $fail('El RIF J debe tener entre 8 y 9 caracteres.');
                    }
                },
            ],
            'fecha_nac' => 'required|date|before:-18 years|after:-100 years',
            'prefijo_tlf' => 'required|in:+58,+57,+1,+34',
            'numero_tlf' => 'required|max:15|regex:/^\d+$/',
            'genero' => 'required|in:Masculino,Femenino,Otro'
        ], [
            'primer_nombre.regex' => 'El primer nombre solo debe contener letras',
            'segundo_nombre.regex' => 'El segundo nombre solo debe contener letras',
            'primer_apellido.regex' => 'El primer apellido solo debe contener letras',
            'segundo_apellido.regex' => 'El segundo apellido solo debe contener letras',
            'fecha_nac.before' => 'Debe ser mayor de 18 años',
            'fecha_nac.after' => 'La edad no puede ser mayor a 100 años',
            'password.confirmed' => 'Las contraseñas no coinciden',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres',
            'password.regex' => 'La contraseña debe tener al menos una mayúscula, un número y un símbolo (@$!%*#?&.)',
            'pregunta_seguridad_2.different' => 'Las preguntas de seguridad no pueden repetirse',
            'pregunta_seguridad_3.different' => 'Las preguntas de seguridad no pueden repetirse',
            'numero_documento.regex' => 'La cédula solo debe contener números',
            'numero_documento.min' => 'La cédula debe tener entre 6 y 12 dígitos',
            'numero_documento.max' => 'La cédula debe tener entre 6 y 12 dígitos',
            'numero_tlf.regex' => 'El teléfono solo debe contener números'
        ]);

        if ($validator->fails()) {
            Log::warning('Validación fallida en registro', $validator->errors()->toArray());
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            DB::beginTransaction();

            // Crear usuario con MD5 dos veces
            $usuario = Usuario::create([
                'rol_id' => $request->rol_id,
                'correo' => $request->correo,
                'password' => $request->password,
                'status' => true
            ]);

            Log::info('Usuario creado', ['id' => $usuario->id]);

            // Crear 3 respuestas de seguridad
            for ($i = 1; $i <= 3; $i++) {
                // Convert to lowercase for case-insensitive comparison
                $respuesta = strtolower(trim($request->input("respuesta_seguridad_$i")));
                RespuestaSeguridad::create([
                    'user_id' => $usuario->id,
                    'pregunta_id' => $request->input("pregunta_seguridad_$i"),
                    'respuesta_hash' => $respuesta, // El modelo aplica md5(md5()) automáticamente
                    'status' => true
                ]);
            }

            Log::info('Respuestas de seguridad creadas');

            // Crear perfil según el rol
            if ($request->rol_id == 2) {
                $this->crearPerfilMedico($usuario->id, $request);
            } else {
                $this->crearPerfilPaciente($usuario->id, $request);
            }

            Log::info('Perfil creado');

            DB::commit();

            // Crear historial de contraseña inicial
            HistorialPassword::create([
                'user_id' => $usuario->id,
                'password_hash' => md5(md5($request->password)),
                'status' => true
            ]);

            // Enviar email de confirmación
            $this->enviarEmailConfirmacion($usuario);

            // Autenticar automáticamente
            Auth::login($usuario);

            // Flag para mostrar toasts solo al registrarse/iniciar sesión
            session(['mostrar_bienvenida_toasts' => true]);

            return $this->redirectByRole($usuario);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error en registro: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return redirect()->back()->with('error', 'Error al registrar: ' . $e->getMessage())->withInput();
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('home')->with('success', 'Sesión cerrada exitosamente');
    }

    public function showRecovery()
    {
        return view('auth.recovery');
    }

    public function sendRecovery(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        
        $usuario = Usuario::where('correo', $request->email)->first();
        
        if (!$usuario) {
            // Anti-enumeración: responder siempre success aunque no exista
            return response()->json([
                'success' => true,
                'message' => 'Si el correo existe en nuestro sistema, recibirás instrucciones de recuperación.'
            ]);
        }

        // Generar token
        $token = Str::random(64);
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $usuario->correo],
            ['token' => $token, 'created_at' => now()]
        );

        // Enviar email
        try {
            $this->enviarEmailRecuperacion($usuario, $token);
            return response()->json(['success' => true, 'message' => 'Enlace enviado correctamente.']);
        } catch (\Exception $e) {
            Log::error('Error en sendRecovery: ' . $e->getMessage());
            Log::error($e->getTraceAsString());
            return response()->json(['success' => false, 'message' => 'Error al enviar el correo: ' . $e->getMessage()], 500);
        }
    }

    public function getSecurityQuestions(Request $request)
    {
        $identifier = $request->identifier;
        
        Log::info('getSecurityQuestions called', [
            'identifier' => $identifier
        ]);
        
        // Try to find user by email first
        $usuario = Usuario::where('correo', $identifier)->first();
        
        // If not found and identifier looks like a cedula (numeric), search in related models
        if (!$usuario && preg_match('/^\d+$/', $identifier)) {
            Log::info('Email not found, searching by cedula in related tables', [
                'identifier' => $identifier
            ]);
            
            // Search in Paciente table
            $paciente = Paciente::where('numero_documento', $identifier)
                                ->where('status', true)
                                ->first();
            
            if ($paciente) {
                $usuario = $paciente->usuario;
                Log::info('User found via Paciente model', [
                    'paciente_id' => $paciente->id,
                    'user_id' => $usuario?->id
                ]);
            }
            
            // If still not found, search in Medico table
            if (!$usuario) {
                $medico = Medico::where('numero_documento', $identifier)
                                ->where('status', true)
                                ->first();
                
                if ($medico) {
                    $usuario = $medico->usuario;
                    Log::info('User found via Medico model', [
                        'medico_id' => $medico->id,
                        'user_id' => $usuario?->id
                    ]);
                }
            }
        }

        Log::info('Usuario search result', [
            'identifier' => $identifier,
            'usuario_found' => $usuario ? 'Yes' : 'No',
            'usuario_id' => $usuario?->id
        ]);

        if (!$usuario) {
            return response()->json(['success' => false, 'message' => 'Usuario no encontrado'], 404);
        }

        $respuestas = RespuestaSeguridad::where('user_id', $usuario->id)
                                        ->with('pregunta')
                                        ->get();
        
        Log::info('Security answers found', [
            'user_id' => $usuario->id,
            'respuestas_count' => $respuestas->count()
        ]);
                                        
        if ($respuestas->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'El usuario no tiene preguntas de seguridad configuradas'], 400);
        }

        $questions = $respuestas->map(function ($respuesta) {
            return [
                'id' => $respuesta->pregunta->id,
                'pregunta' => $respuesta->pregunta->pregunta
            ];
        });

        return response()->json([
            'success' => true,
            'user_id' => $usuario->id,
            'questions' => $questions
        ]);
    }

    public function verifySecurityAnswers(Request $request)
    {
        $userId = $request->user_id;
        $usuario = Usuario::find($userId);

        if (!$usuario) {
            return response()->json(['success' => false, 'message' => 'Usuario no válido'], 404);
        }

        // Check if account is already locked
        if ($usuario->status == 2 && $usuario->blocked_until && $usuario->blocked_until > now()) {
            return response()->json([
                'success' => false,
                'locked' => true,
                'message' => 'Cuenta bloqueada temporalmente por seguridad.',
                'blocked_until' => $usuario->blocked_until->format('d/m/Y H:i')
            ], 403);
        }
        
        // Get current attempts from session (scoped to user_id)
        $sessionKey = "recovery_attempts_{$userId}";
        $attempts = session($sessionKey, 0);
        
        $allCorrect = true;
        

        for ($i = 1; $i <= 3; $i++) {
            $questionId = $request->input("question_{$i}_id");
            $userAnswer = $request->input("answer_{$i}");

            if (!$questionId || !$userAnswer) {
                continue;
            }

            $respuestaAlmacenada = RespuestaSeguridad::where('user_id', $usuario->id)
                ->where('pregunta_id', $questionId)
                ->first();

            if (!$respuestaAlmacenada) {
                $allCorrect = false;
                break;
            }
            
            // Convert to lowercase for case-insensitive comparison
            $normalizedAnswer = strtolower(trim($userAnswer));
            
            // Check 1: Normalized (Standard - lowercase + trimmed)
            $normalizedHash = md5(md5($normalizedAnswer));
            
            // Check 2: Trimmed only (Legacy support - preserves case)
            $trimmedHash = md5(md5(trim($userAnswer)));
            
            // Check 3: Raw (Legacy support - includes spaces and case)
            $rawHash = md5(md5($userAnswer));
            
            Log::info("Verification attempt for security question", [
                'user_id' => $usuario->id,
                'pregunta_id' => $questionId,
                'matched' => (
                    $respuestaAlmacenada->respuesta_hash === $normalizedHash ||
                    $respuestaAlmacenada->respuesta_hash === $trimmedHash ||
                    $respuestaAlmacenada->respuesta_hash === $rawHash
                )
            ]);
            
            if ($respuestaAlmacenada->respuesta_hash !== $normalizedHash && 
                $respuestaAlmacenada->respuesta_hash !== $trimmedHash &&
                $respuestaAlmacenada->respuesta_hash !== $rawHash) {
                $allCorrect = false;
                break;
            }
        }

        if ($allCorrect) {
            // Clear attempts on success
            session()->forget($sessionKey);
            
            $token = Str::random(64);
            DB::table('password_reset_tokens')->updateOrInsert(
                ['email' => $usuario->correo],
                ['token' => $token, 'created_at' => now()]
            );

            return response()->json([
                'success' => true,
                'token' => $token,
                'email' => $usuario->correo
            ]);
        } else {
            // Increment attempts
            $attempts++;
            session([$sessionKey => $attempts]);
            
            Log::warning('Failed security question attempt', [
                'user_id' => $userId,
                'attempts' => $attempts,
                'ip' => $request->ip()
            ]);
            
            // Check if this is the 3rd failed attempt
            if ($attempts >= 3) {
                // Lock the account
                $blockedUntil = now()->addHours(24);
                $usuario->status = 2; // locked
                $usuario->blocked_until = $blockedUntil;
                $usuario->lock_reason = 'Múltiples intentos fallidos de recuperación de contraseña';
                $usuario->save();
                
                // Clear attempts
                session()->forget($sessionKey);
                
                // Send notification
                try {
                    $usuario->notify(new \App\Notifications\AlertaCuentaBloqueada(
                        $blockedUntil,
                        'Múltiples intentos fallidos de recuperación de contraseña'
                    ));
                } catch (\Exception $e) {
                    Log::error('Error sending account locked notification: ' . $e->getMessage());
                }
                
                Log::alert('Account locked due to failed recovery attempts', [
                    'user_id' => $usuario->id,
                    'email' => $usuario->correo,
                    'blocked_until' => $blockedUntil,
                    'ip' => $request->ip()
                ]);
                
                return response()->json([
                    'success' => false,
                    'locked' => true,
                    'message' => 'Cuenta bloqueada por 24 horas debido a múltiples intentos fallidos.',
                    'blocked_until' => $blockedUntil->format('d/m/Y H:i')
                ], 403);
            }
            
            $attemptsRemaining = 3 - $attempts;
            return response()->json([
                'success' => false,
                'message' => 'Respuestas incorrectas',
                'attempts_remaining' => $attemptsRemaining
            ], 400);
        }
    }

    public function showResetPassword($token)
    {
        $reset = DB::table('password_reset_tokens')->where('token', $token)->first();

        if (!$reset) {
            return redirect()->route('login')->with('error', 'Token inválido o expirado');
        }

        return view('auth.reset-password', ['token' => $token, 'email' => $reset->email]);
    }

    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required',
            'email' => 'required|email',
            'password' => [
                'required',
                'min:8',
                'confirmed',
                'regex:/[A-Z]/',      // Al menos una mayúscula
                'regex:/[0-9]/',      // Al menos un número
                'regex:/[@$!%*#?&.]/' // Al menos un símbolo
            ]
        ], [
            'password.confirmed' => 'Las contraseñas no coinciden',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres',
            'password.regex' => 'La contraseña debe contener al menos una mayúscula, un número y un símbolo (@$!%*#?&.)'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        $reset = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->where('token', $request->token)
            ->first();

        if (!$reset) {
            return redirect()->back()->with('error', 'Token inválido o expirado');
        }

        $usuario = Usuario::where('correo', $request->email)->first();

        if (!$usuario) {
            return redirect()->back()->with('error', 'Usuario no encontrado');
        }

        // --- VALIDACIÓN DE HISTORIAL DE CONTRASEÑAS (Últimas 5) ---
        $newPasswordHash = md5(md5($request->password));
        
        // 1. Verificar contra la contraseña ACTUAL
        if ($usuario->password === $newPasswordHash) {
            return redirect()->back()->with('error', 'La nueva contraseña no puede ser igual a tu contraseña actual. Por favor, elige una diferente.');
        }

        // 2. Verificar en las últimas 5 contraseñas cambiadas
        $latestPasswords = HistorialPassword::where('user_id', $usuario->id)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->pluck('password_hash');

        if ($latestPasswords->contains($newPasswordHash)) {
            return redirect()->back()->with('error', 'La nueva contraseña no puede ser una de las últimas 5 que has usado. Por seguridad, utiliza una diferente.');
        }

        // --- INACTIVAR HISTORIAL ANTIGUO ---
        HistorialPassword::where('user_id', $usuario->id)
                         ->where('status', true)
                         ->update(['status' => false]);

        // --- ACTUALIZAR CONTRASEÑA Y GUARDAR NUEVO HISTORIAL ---
        $usuario->update(['password' => $request->password]);

        HistorialPassword::create([
            'user_id' => $usuario->id,
            'password_hash' => $newPasswordHash,
            'status' => true
        ]);

        // --- ELIMINAR TOKEN DE RESETEO ---
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        // --- NOTIFICACIONES ---
        try {
            // Notificación por Email usando la nueva clase
            $usuario->notify(new PasswordChangedNotification());
            
            // Notificación de Sistema (Alerta al próximo login)
            if ($usuario->id) {
                $usuario->notify(new \App\Notifications\AlertaPasswordCambiada());
            }
        } catch (\Exception $e) {
            Log::error('Error enviando notificaciones post-reseteo: ' . $e->getMessage());
        }

        return redirect()->route('login')->with('success', 'Contraseña restablecida exitosamente. Ya puedes iniciar sesión.');
    }

    private function crearPerfilMedico($userId, $request)
    {
        Medico::create([
            'user_id' => $userId,
            'primer_nombre' => $request->primer_nombre,
            'segundo_nombre' => $request->segundo_nombre ?? null,
            'primer_apellido' => $request->primer_apellido,
            'segundo_apellido' => $request->segundo_apellido ?? null,
            'tipo_documento' => $request->tipo_documento,
            'numero_documento' => $request->numero_documento,
            'fecha_nac' => $request->fecha_nac,
            'estado_id' => $request->estado_id ?? null,
            'ciudad_id' => $request->ciudad_id ?? null,
            'prefijo_tlf' => $request->prefijo_tlf,
            'numero_tlf' => $request->numero_tlf,
            'genero' => $request->genero,
            'status' => true
        ]);
    }

    private function crearPerfilPaciente($userId, $request)
    {
        Paciente::create([
            'user_id' => $userId,
            'primer_nombre' => $request->primer_nombre,
            'segundo_nombre' => $request->segundo_nombre,
            'primer_apellido' => $request->primer_apellido,
            'segundo_apellido' => $request->segundo_apellido,
            'tipo_documento' => $request->tipo_documento,
            'numero_documento' => $request->numero_documento,
            'fecha_nac' => $request->fecha_nac,
            'estado_id' => $request->estado_id ?? null,
            'ciudad_id' => $request->ciudad_id ?? null,
            'municipio_id' => $request->municipio_id ?? null,
            'parroquia_id' => $request->parroquia_id ?? null,
            'direccion_detallada' => $request->direccion ?? null,
            'prefijo_tlf' => $request->prefijo_tlf,
            'numero_tlf' => $request->numero_tlf,
            'genero' => $request->genero,
            'ocupacion' => $request->ocupacion ?? null,
            'estado_civil' => $request->estado_civil ?? null,
            'status' => true
        ]);
    }

    private function redirectByRole($usuario)
    {
        switch ($usuario->rol_id) {
            case 1:
                return redirect()->route('admin.dashboard')->with('success', 'Bienvenido Administrador');
            case 2:
                return redirect()->route('medico.dashboard')->with('success', 'Bienvenido Doctor');
            case 3:
                return redirect()->route('paciente.dashboard')->with('success', 'Bienvenido Paciente');
            default:
                return redirect()->route('home');
        }
    }

    private function enviarEmailConfirmacion($usuario)
    {
        try {
            $destinatario = $usuario->paciente ?: ($usuario->medico ?: $usuario);
            $destinatario->notify(new \App\Notifications\BienvenidaSistema($usuario));
        } catch (\Exception $e) {
            Log::error('Error enviando notificación de bienvenida: ' . $e->getMessage());
        }
    }

    private function enviarEmailRecuperacion($usuario, $token)
    {
        $resetUrl = route('password.reset', ['token' => $token, 'email' => $usuario->correo]);
        
        Mail::send('emails.recuperar-password', [
            'usuario' => $usuario,
            'resetUrl' => $resetUrl
        ], function($message) use ($usuario) {
            $message->to($usuario->correo)
                    ->subject('Recuperación de Contraseña - Sistema Médico');
        });
    }

    private function enviarEmailConfirmacionCambio($usuario)
    {
        try {
            Mail::send('emails.confirmacion-cambio-password', ['usuario' => $usuario], function ($message) use ($usuario) {
                $message->to($usuario->correo)
                    ->subject('Contraseña Cambiada - Sistema Médico');
            });
        } catch (\Exception $e) {
            Log::error('Error enviando email de confirmación de cambio: ' . $e->getMessage());
        }
    }
    public function verificarCorreo(Request $request)
    {
        $correo = $request->correo;
        $existe = Usuario::where('correo', $correo)->exists();

        return response()->json(['existe' => $existe]);
    }

    // AJAX Validations for Register
    public function checkEmail(Request $request)
    {
        $exists = Usuario::where('correo', $request->email)->exists();
        return response()->json(['exists' => $exists]);
    }

    public function checkDocument(Request $request)
    {
        // Check in Paciente table
        $exists = Paciente::where('tipo_documento', $request->tipo)
                          ->where('numero_documento', $request->numero)
                          ->exists();
        
        if (!$exists) {
            // Check in Medico table
            $exists = Medico::where('tipo_documento', $request->tipo)
                            ->where('numero_documento', $request->numero)
                            ->exists();
        }

        return response()->json(['exists' => $exists]);
    }
}
