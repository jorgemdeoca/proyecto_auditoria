# Plan de Ejecución del Módulo de Auditoría (Jorge y Jesús)

Este plan de trabajo está diseñado para **paralelizar el desarrollo** del Módulo de Auditoría y Reportes, dividiendo las responsabilidades arquitectónicas para evitar conflictos de código (merge conflicts) y dependencias bloqueantes.

**Estrategia de División:** 
- **Jorge** se enfocará en el "Motor" (Backend profundo, Base de Datos, Observers, Traits, Middleware).
- **Jesús** se enfocará en la "Presentación y Control" (Rutas, Controladores, Vistas Blade, PDFs y Excel).

Ambos pueden trabajar al mismo tiempo tocando archivos completamente diferentes.

---

## FASE 1: Estructura Base (Simultáneo)
*Objetivo: Dejar listos los cimientos de base de datos y enrutamiento.*

### 👨‍💻 Tareas de Jorge (Base de Datos y Modelos)
*Archivos afectados: `database/migrations/*`, `app/Models/AuditLog.php`, `AuthLog.php`, `ReadAuditLog.php`.*
1. Crear las 3 migraciones nuevas (`audit_logs`, `auth_logs`, `read_audit_logs`).
2. Crear la migración para añadir `failed_login_count` a la tabla `usuarios`.
3. Crear los 3 Modelos de Eloquent (`AuditLog`, `AuthLog`, `ReadAuditLog`) con sus respectivas configuraciones (timestamps apagados, casts de JSON) y Scopes de búsqueda básicos.

### 👨‍💻 Tareas de Jesús (Rutas y Esqueleto UI)
*Archivos afectados: `routes/web.php`, `app/Http/Controllers/Admin/AuditoriaController.php`, `resources/views/layouts/admin.blade.php`.*
1. Crear el grupo de rutas en `web.php` bajo el middleware de administrador (Rutas para index, citas, pagos, exportaciones, etc).
2. Crear el `AuditoriaController` solo con la estructura de las funciones (que por ahora retornen un `view()` a archivos en blanco).
3. Añadir el enlace "Auditoría (Seguridad)" en el sidebar del panel de administrador (`admin.blade.php`).

---

## FASE 2: Desarrollo Core vs. Interfaz Visual (Simultáneo)
*Objetivo: Jorge captura los datos en el sistema; Jesús diseña las pantallas donde se verán.*

### 👨‍💻 Tareas de Jorge (Motor de Captura)
*Archivos afectados: `app/Traits/HasAuditTrail.php`, `app/Observers/AuditableObserver.php`, `app/Listeners/*`, `app/Providers/EventServiceProvider.php`, y todos los Modelos a auditar.*
1. Crear el trait `HasAuditTrail`.
2. Crear el `AuditableObserver` con la lógica de atrapar `created`, `updated`, `deleted` y guardar los snapshots en JSON.
3. Importar el trait `HasAuditTrail` dentro de los modelos requeridos (`Cita`, `Pago`, `FacturaPaciente`, `HistoriaClinicaBase`, `EvolucionClinica`, `TasaDolar`).
4. Crear los 3 Listeners de autenticación (`LogSuccessfulLogin`, `LogFailedLogin`, `LogLogout`) y registrarlos.

### 👨‍💻 Tareas de Jesús (Maquetación Blade)
*Archivos afectados: `resources/views/admin/auditoria/*`.*
1. Maquetar `index.blade.php` con las 4 tarjetas de KPIs (indicadores) simulando datos (hardcodeados por ahora) y la información del módulo.
2. Maquetar el componente `partials/_filtros.blade.php` (formulario de fechas y select de eventos).
3. Maquetar las tablas de datos en `citas.blade.php`, `pagos.blade.php`, `acceso_medico.blade.php` y `auth_logs.blade.php`. (Puede usar datos falsos o colecciones vacías para que el HTML no rompa).

---

## FASE 3: Lógica Compleja y Reportes (Simultáneo)
*Objetivo: Conectar el Frontend de Jesús con los Modelos de Jorge, y cerrar las lógicas de negocio complejas.*

### 👨‍💻 Tareas de Jorge (Middlewares y Auth Logic)
*Archivos afectados: `app/Http/Middleware/AuditReadAccess.php`, `app/Http/Kernel.php`, `app/Http/Controllers/AuthController.php`.*
1. Crear el Middleware `AuditReadAccess` para registrar las lecturas de las historias clínicas. Registrarlo en el Kernel.
2. Ir al `AuthController` y reemplazar la lógica vieja de bloqueo por intentos fallidos (basada en sesión) por la nueva lógica apuntando al campo `failed_login_count` de la base de datos.

### 👨‍💻 Tareas de Jesús (Filtros Reales y Exportaciones)
*Archivos afectados: `app/Http/Controllers/Admin/AuditoriaController.php`, `app/Exports/*`, `resources/views/admin/auditoria/pdf/*`.*
1. En el `AuditoriaController`, reemplazar los datos de prueba por consultas reales a los Modelos de Jorge (`AuditLog`, `AuthLog`, etc).
2. Implementar la lógica del `tipo_admin`: Si es Root ve todos los consultorios, si es Admin usa el scope para ver solo los suyos.
3. Crear las 3 clases de Exportación Excel usando `Maatwebsite\Excel`.
4. Crear las 2 vistas de PDF (`citas` y `pagos`) y la lógica de generación con `barryvdh/laravel-dompdf`.

---

## FASE 4: Integración y Verificación Cruzada (En Equipo)
*Objetivo: Comprobar que el trabajo de ambos embona perfectamente.*

1. **Unificación de Ramas:** Unir el código de Jorge y Jesús. Correr `php artisan migrate`.
2. **Jorge** navega el sistema normal (crea citas, edita pagos, hace login fallido, abre historias clínicas).
3. **Jesús** revisa su panel de Auditoría para confirmar que las trazas creadas por las acciones de Jorge aparecen correctamente, y prueba exportar un Excel y un PDF con esos datos.
