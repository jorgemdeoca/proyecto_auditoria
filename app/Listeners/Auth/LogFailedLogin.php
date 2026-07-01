<?php

namespace App\Listeners\Auth;

use Illuminate\Auth\Events\Failed;
use Illuminate\Support\Facades\DB;

class LogFailedLogin
{
    public function handle(Failed $event): void
    {
        try {
            DB::table('auth_logs')->insert([
                'user_id'    => $event->user?->id,
                'correo'     => $event->credentials['correo'] ?? $event->credentials['email'] ?? 'desconocido',
                'event_type' => 'LOGIN_FAIL',
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'created_at' => now(),
            ]);
        } catch (\Throwable $e) {
            \Log::warning('[LogFailedLogin] ' . $e->getMessage());
        }
    }
}
