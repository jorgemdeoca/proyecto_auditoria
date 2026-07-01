<?php

namespace App\Listeners\Auth;

use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\DB;

class LogSuccessfulLogin
{
    public function handle(Login $event): void
    {
        try {
            DB::table('auth_logs')->insert([
                'user_id'    => $event->user->id,
                'correo'     => $event->user->correo,
                'event_type' => 'LOGIN_OK',
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'created_at' => now(),
            ]);

            // Resetear contador de intentos fallidos
            $event->user->update([
                'failed_login_count' => 0,
                'last_failed_at'     => null,
            ]);
        } catch (\Throwable $e) {
            \Log::warning('[LogSuccessfulLogin] ' . $e->getMessage());
        }
    }
}
