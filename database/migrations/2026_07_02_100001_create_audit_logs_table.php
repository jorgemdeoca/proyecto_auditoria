<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->string('auditable_type');          // Ej: App\Models\Cita
            $table->unsignedBigInteger('auditable_id'); // ID del registro auditado
            $table->unsignedBigInteger('causer_id')->nullable(); // ID del usuario que hizo el cambio
            $table->string('causer_nombre')->nullable();
            $table->string('causer_rol')->nullable();
            $table->string('event');                   // created | updated | deleted | state_changed
            $table->string('modulo')->nullable();      // citas | pagos | facturacion | historia_clinica | configuracion
            $table->json('old_values')->nullable();    // Snapshot antes del cambio
            $table->json('new_values')->nullable();    // Snapshot después del cambio
            $table->string('ip_address')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index(['auditable_type', 'auditable_id'], 'idx_auditable');
            $table->index('causer_id');
            $table->index('modulo');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
