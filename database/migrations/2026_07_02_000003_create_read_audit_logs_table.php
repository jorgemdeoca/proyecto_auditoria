<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('read_audit_logs', function (Blueprint $table) {
            $table->bigIncrements('id');

            // ¿QUIÉN leyó la historia?
            $table->unsignedBigInteger('reader_id');
            $table->string('reader_nombre', 200)->nullable();
            $table->string('reader_rol', 50)->nullable(); // 'medico', 'admin', 'supervisor', etc.

            // ¿QUÉ recurso leyó?
            $table->string('resource_type', 100);          // 'HistoriaClinicaBase', 'EvolucionClinica'
            $table->unsignedBigInteger('resource_id');

            // Desnormalizado para filtrar por paciente sin JOIN adicional
            $table->unsignedBigInteger('paciente_id')->nullable();
            $table->string('paciente_nombre', 200)->nullable();

            // Contexto de la acción
            $table->string('ip_address', 45)->nullable();
            $table->string('ruta_accedida', 200)->nullable(); // ej: 'historia.show'

            // Tabla append-only: solo created_at
            $table->timestamp('created_at')->useCurrent();

            // Índices para el panel de acceso a historiales
            $table->index(['reader_id', 'created_at'], 'idx_read_reader');
            $table->index(['paciente_id', 'created_at'], 'idx_read_paciente');
            $table->index(['resource_type', 'resource_id'], 'idx_read_resource');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('read_audit_logs');
    }
};
