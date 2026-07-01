<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('read_audit_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('reader_id')->default(0); // ID del usuario que leyó
            $table->string('reader_nombre')->nullable();
            $table->string('reader_rol')->nullable();
            $table->string('resource_type');             // HistoriaClinicaBase | EvolucionClinica
            $table->unsignedBigInteger('resource_id');   // ID del recurso leído
            $table->unsignedBigInteger('paciente_id')->nullable();
            $table->string('paciente_nombre')->nullable();
            $table->string('ip_address')->nullable();
            $table->string('ruta_accedida')->nullable(); // Nombre de la ruta de Laravel
            $table->timestamp('created_at')->useCurrent();

            $table->index('reader_id');
            $table->index('resource_type');
            $table->index('paciente_id');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('read_audit_logs');
    }
};
