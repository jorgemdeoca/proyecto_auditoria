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
                'user_id'    => $event->user?->id,
                'correo'     => $event->user?->correo ?? 'desconocido',
                'event_type' => 'LOGOUT',
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'created_at' => now(),
            ]);
        } catch (\Throwable $e) {
            \Log::warning('[LogLogout] ' . $e->getMessage());
        }
    }
}
