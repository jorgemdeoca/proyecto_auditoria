<?php

namespace App\Listeners\Auth;

use Illuminate\Auth\Events\Logout;
use Illuminate\Support\Facades\DB;

class LogLogout
{
    public function handle(Logout $event): void
    {
        try {
            DB::table('auth_logs')->insert([
                'user_id'          => $event->user?->id,
                'correo_intentado' => $event->user?->correo ?? '',
                'event_type'       => 'LOGOUT',
                'ip_address'       => request()->ip(),
                'user_agent'       => substr(request()->userAgent() ?? '', 0, 512),
                'session_id'       => session()->getId(),
                'created_at'       => now(),
            ]);
        } catch (\Throwable $e) {
            \Log::warning('[AuthLog] Error al registrar LOGOUT: ' . $e->getMessage());
        }
    }
}
