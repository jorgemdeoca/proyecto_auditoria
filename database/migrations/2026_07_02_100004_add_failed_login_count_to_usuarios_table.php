<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('usuarios', function (Blueprint $table) {
            // Solo agregar si no existen ya
            if (!Schema::hasColumn('usuarios', 'failed_login_count')) {
                $table->unsignedTinyInteger('failed_login_count')->default(0)->after('status');
            }
            if (!Schema::hasColumn('usuarios', 'last_failed_at')) {
                $table->timestamp('last_failed_at')->nullable()->after('failed_login_count');
            }
            if (!Schema::hasColumn('usuarios', 'locked_until')) {
                $table->timestamp('locked_until')->nullable()->after('last_failed_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('usuarios', function (Blueprint $table) {
            $table->dropColumnIfExists('failed_login_count');
            $table->dropColumnIfExists('last_failed_at');
            $table->dropColumnIfExists('locked_until');
        });
    }
};
