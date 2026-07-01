<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('auth_logs', function (Blueprint $table) {
            $table->bigIncrements('id');

            // Puede ser null si el correo ingresado no corresponde a ningún usuario
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('correo_intentado', 150);

            $table->enum('event_type', [
                'LOGIN_OK',
                'LOGIN_FAIL',
                'LOGOUT',
                'LOCKOUT',
                'UNLOCK',
            ]);

            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent', 512)->nullable();
            $table->string('session_id', 100)->nullable();

            // Tabla append-only: solo created_at
            $table->timestamp('created_at')->useCurrent();

            // Índices para el panel de auth y detección de intentos fallidos
            $table->index(['user_id', 'created_at'], 'idx_auth_user');
            $table->index(['ip_address', 'event_type', 'created_at'], 'idx_auth_ip_event');
            $table->index(['event_type', 'created_at'], 'idx_auth_event');
            $table->index(['correo_intentado', 'created_at'], 'idx_auth_correo');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('auth_logs');
    }
};
