# Módulo de Auditoría y Reportes (ReservaMedica)

El objetivo es implementar un "Asistente de Auditoría y Reportes" centralizado para los roles `admin` y `root` de la aplicación ReservaMedica. 
Dado que es un proyecto universitario, **se prescindirá de controles de seguridad o vulnerabilidad complejos** (las contraseñas se mantendrán con `MD5(MD5)` como exige la rúbrica), y el enfoque estará 100% en que el registro de la auditoría y los reportes funcionen a la perfección.

## Requerimientos y Reglas de Negocio a Implementar
1. **Roles y Alcance**:
   - `Root`: Puede ver reportes y auditorías de **todos** los consultorios.
   - Otros roles (`Administrador`, `Supervisor`, `Recepcionista`): Solo pueden ver reportes de los **consultorios que tienen asignados**.
2. **Entidades Auditadas**: `Citas`, `Pagos`, `FacturasPacientes`, `HistoriaClinicaBase`, `EvolucionClinica`, `TasaDolar`.
3. **Monitoreo de Autenticación**: Logs de inicio de sesión exitoso, fallido y cierres de sesión.
4. **Monitoreo de Lectura**: Quién accede a visualizar las historias clínicas de los pacientes.
5. **Reportes**: Dashboard con KPIs, filtros por fecha, y exportación a Excel y PDF.

## Proposed Changes

### 1. Capa de Base de Datos (Migraciones)
Se crearán 4 migraciones para no sobrecargar el diseño original:

#### [NEW] database/migrations/xxxx_xx_xx_xxxxxx_create_audit_logs_table.php
Tabla `audit_logs` con estructura polimórfica: `auditable_type`, `auditable_id`, `causer_id` (quién hizo el cambio), `event` (created/updated/deleted/state_changed), `old_values` (JSON), `new_values` (JSON).

#### [NEW] database/migrations/xxxx_xx_xx_xxxxxx_create_auth_logs_table.php
Tabla `auth_logs` para llevar un registro de `LOGIN_OK`, `LOGIN_FAIL` y `LOGOUT`, junto con IP.

#### [NEW] database/migrations/xxxx_xx_xx_xxxxxx_create_read_audit_logs_table.php
Tabla `read_audit_logs` enfocada en quién, cuándo y a qué paciente le abrió la historia clínica.

#### [NEW] database/migrations/xxxx_xx_xx_xxxxxx_add_failed_login_fields_to_usuarios.php
En lugar de manejar los intentos fallidos en la sesión (que puede evadirse limpiando cookies), agregaremos `failed_login_count` a la tabla `usuarios` para el bloqueo automático.

---

### 2. Capa de Lógica Backend (Observers y Middlewares)

#### [NEW] app/Traits/HasAuditTrail.php
Trait que se inyectará en los modelos (Cita, Pago, etc.) para que automáticamente registren sus cambios en la tabla de auditoría.

#### [NEW] app/Observers/AuditableObserver.php
Observer que detectará cambios (created/updated/deleted), sacará un "snapshot" de lo que cambió, y usará `DB::table` para insertarlo silenciosamente sin ralentizar el sistema con llamadas extra de Eloquent.

#### [MODIFY] Modelos (Cita, Pago, FacturaPaciente, HistoriaClinicaBase, EvolucionClinica, TasaDolar)
Se agregará el `use HasAuditTrail` a cada uno de estos modelos.

#### [NEW] app/Listeners/Auth/LogSuccessfulLogin.php (y relacionados)
Listeners que capturarán eventos de autenticación de Laravel. Se registrarán en `EventServiceProvider`.

#### [MODIFY] app/Http/Controllers/AuthController.php
Modificar la lógica de límite de intentos (rate limiting) para usar la columna `failed_login_count` de la base de datos, en lugar del uso nativo de sesiones que tenías programado.

#### [NEW] app/Http/Middleware/AuditReadAccess.php
Middleware que registrará silenciosamente cuándo alguien entra a leer una historia clínica en `ReadAuditLog`. Se agregará el alias en `app/Http/Kernel.php`.

---

### 3. Capa UI, Reportes y Exportación

#### [NEW] app/Http/Controllers/Admin/AuditoriaController.php
Controlador central con la lógica para filtrar resultados:
- Evaluará `auth()->user()->tipo_admin`. Si es 'Root', muestra todo. Si es otro, aplicará un `scope` a los queries limitando por `consultorio_id`.

#### [MODIFY] routes/web.php
Grupo de rutas para `/admin/auditoria/` y subrutas.

#### [NEW] app/Exports/AuditCitasExport.php (y relacionados)
Clases de `Maatwebsite\Excel` para manejar la exportación de tablas filtradas a un `.xlsx`.

#### [NEW] resources/views/admin/auditoria/index.blade.php (y sub-vistas)
Vistas en Blade.
- **index**: KPIs (tarjetas con totales de citas modificadas, intentos fallidos, etc).
- **citas / pagos**: Tablas listando las trazas.
- **partials/_filtros**: Componente reutilizable para filtrar por fecha y consultorio.
- **pdf/citas.blade.php**: Plantilla HTML que usará `barryvdh/laravel-dompdf` para generar PDFs gerenciales.

#### [MODIFY] resources/views/layouts/admin.blade.php
Añadir el enlace al "Asistente de Auditoría" en la barra lateral (sidebar) debajo de "Reportes y Estadísticas".

---

## Verification Plan

### Automated Tests
* N/A (Entorno de desarrollo académico sin tests automatizados)

### Manual Verification
1. Ingresaré con una cuenta "Root" y probaré ver auditorías de todos los consultorios.
2. Ingresaré con una cuenta "Administrador" normal y comprobaré que el filtrado funciona.
3. Modificaré el estado de una Cita o Pago y verificaré que aparezca la traza en el panel.
4. Exportaré un archivo PDF y un Excel.
5. Equivocaré mi contraseña 5 veces para verificar que funciona el log de Auth y se bloquea la cuenta temporalmente.

## Open Questions

Ninguna. Con tu última confirmación, los requerimientos están 100% claros (uso del `tipo_admin` en 'Root' vs. 'Administrador'/'Supervisor'/'Recepcionista', preservación del cifrado `MD5`, y enfoque total en que la auditoría funcione perfectamente).
