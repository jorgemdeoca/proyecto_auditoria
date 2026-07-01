<?php

namespace App\Traits;

use App\Observers\AuditableObserver;

trait HasAuditTrail
{
    /**
     * Se ejecuta automáticamente al hacer boot del modelo gracias a la
     * convención bootNombreTrait() de Laravel.
     */
    public static function bootHasAuditTrail(): void
    {
        static::observe(AuditableObserver::class);
    }

    public function getAuditExclude(): array
    {
        return property_exists($this, 'auditExclude') 
            ? $this->auditExclude 
            : ['updated_at', 'status'];
    }

    public function getAuditModulo(): string
    {
        return property_exists($this, 'auditModulo') && $this->auditModulo
            ? $this->auditModulo 
            : strtolower(class_basename($this));
    }
}
