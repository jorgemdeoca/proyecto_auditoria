<?php

namespace App\Listeners\Auth;

use Illuminate\Auth\Events\Failed;
use Illuminate\Support\Facades\DB;

class LogFailedLogin
{
    public function handle(Failed $event): void
    {
        try {
            $correoIntentado = $event->credentials['correo']
                ?? $event->credentials['email']
                ?? '';

            DB::table('auth_logs')->insert([
                'user_id'          => $event->user?->id,
                'correo_intentado' => $correoIntentado,
                'event_type'       => 'LOGIN_FAIL',
                'ip_address'       => request()->ip(),
                'user_agent'       => substr(request()->userAgent() ?? '', 0, 512),
                'session_id'       => session()->getId(),
                'created_at'       => now(),
            ]);
        } catch (\Throwable $e) {
            \Log::warning('[AuthLog] Error al registrar LOGIN_FAIL: ' . $e->getMessage());
        }
    }
}
