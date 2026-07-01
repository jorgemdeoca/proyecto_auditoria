<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('usuarios', function (Blueprint $table) {
            // Contador persistente de intentos fallidos (reemplaza el counter basado en sesión)
            $table->tinyInteger('failed_login_count')->default(0)->after('lock_reason');
            $table->timestamp('last_failed_at')->nullable()->after('failed_login_count');
        });
    }

    public function down(): void
    {
        Schema::table('usuarios', function (Blueprint $table) {
            $table->dropColumn(['failed_login_count', 'last_failed_at']);
        });
    }
};
