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
                'user_id'          => $event->user->id,
                'correo_intentado' => $event->user->correo,
                'event_type'       => 'LOGIN_OK',
                'ip_address'       => request()->ip(),
                'user_agent'       => substr(request()->userAgent() ?? '', 0, 512),
                'session_id'       => session()->getId(),
                'created_at'       => now(),
            ]);
        } catch (\Throwable $e) {
            \Log::warning('[AuthLog] Error al registrar LOGIN_OK: ' . $e->getMessage());
        }
    }
}
