<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->bigIncrements('id');

            // Relación polimórfica: ¿QUÉ entidad fue afectada?
            $table->string('auditable_type', 100);   // App\Models\Cita, App\Models\Pago, etc.
            $table->unsignedBigInteger('auditable_id');

            // ¿QUIÉN realizó la acción?
            $table->unsignedBigInteger('causer_id')->nullable();
            $table->string('causer_nombre', 200)->nullable();

            // ¿QUÉ tipo de acción?
            $table->string('event', 50);             // created, updated, deleted, state_changed
            $table->string('modulo', 50)->nullable(); // citas, pagos, historia_clinica, configuracion

            // Snapshot JSON de los valores antes y después del cambio
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();
            $table->string('motivo', 255)->nullable();

            // Contexto de red
            $table->string('ip_address', 45)->nullable();
            $table->string('session_id', 100)->nullable();

            // Tabla append-only: solo created_at, sin updated_at
            $table->timestamp('created_at')->useCurrent();

            // Índices para consultas rápidas en el panel de auditoría
            $table->index(['auditable_type', 'auditable_id', 'created_at'], 'idx_auditable');
            $table->index(['causer_id', 'created_at'], 'idx_causer');
            $table->index(['modulo', 'event', 'created_at'], 'idx_modulo_event');
            $table->index(['ip_address', 'created_at'], 'idx_ip');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
