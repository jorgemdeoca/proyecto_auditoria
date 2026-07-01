<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DatosPagoMedico extends Model
{
    use HasFactory;

    protected $table = 'datos_pago_medico';
    protected $fillable = [
        'medico_id',
        'banco',
        'tipo_cuenta',
        'numero_cuenta',
        'titular',
        'cedula',
        'metodo_pago_id',
        'prefijo_tlf',
        'numero_tlf'
    ];

    protected $casts = [
        'metodos_habilitados' => 'array',
        'activo' => 'boolean',
        'verificado' => 'boolean',
        'fecha_verificacion' => 'date'
    ];

    /**
     * Relación con el médico
     */
    public function medico()
    {
        return $this->belongsTo(Medico::class);
    }

    /**
     * Verificar si un método de pago está habilitado
     */
    public function tieneMetodoHabilitado($metodo)
    {
        return in_array($metodo, $this->metodos_habilitados ?? []);
    }

    /**
     * Obtener métodos de pago disponibles
     */
    public function getMetodosDisponiblesAttribute()
    {
        $metodos = [];
        
        if ($this->tieneMetodoHabilitado('transferencia') && $this->banco_nombre) {
            $metodos['transferencia'] = [
                'nombre' => 'Transferencia Bancaria',
                'datos' => [
                    'banco' => $this->banco_nombre,
                    'tipo_cuenta' => $this->cuenta_tipo,
                    'numero' => $this->cuenta_numero,
                    'titular' => $this->titular_cuenta,
                    'cedula' => $this->cedula_titular
                ]
            ];
        }

        if ($this->tieneMetodoHabilitado('pago_movil') && $this->pm_numero) {
            $metodos['pago_movil'] = [
                'nombre' => 'Pago Móvil',
                'datos' => [
                    'operadora' => $this->pm_operadora,
                    'numero' => $this->pm_numero,
                    'cedula' => $this->pm_cedula
                ]
            ];
        }

        if ($this->tieneMetodoHabilitado('efectivo')) {
            $metodos['efectivo'] = [
                'nombre' => 'Efectivo',
                'datos' => [
                    'observaciones' => $this->efectivo_observaciones
                ]
            ];
        }

        return $metodos;
    }

    /**
     * Obtener método preferido con sus datos
     */
    public function getMetodoPreferidoCompleto()
    {
        $metodos = $this->metodos_disponibles;
        $metodoPreferido = $this->metodo_preferido;
        
        if ($metodoPreferido && isset($metodos[$metodoPreferido])) {
            return $metodos[$metodoPreferido];
        }

        // Si no hay preferido, devolver el primero disponible
        return !empty($metodos) ? array_values($metodos)[0] : null;
    }

    /**
     * Validar que los datos de pago estén completos
     */
    public function validarDatosPago()
    {
        $errores = [];

        if ($this->tieneMetodoHabilitado('transferencia')) {
            if (!$this->banco_nombre) $errores[] = 'Nombre del banco es requerido';
            if (!$this->cuenta_numero) $errores[] = 'Número de cuenta es requerido';
            if (!$this->titular_cuenta) $errores[] = 'Titular de cuenta es requerido';
        }

        if ($this->tieneMetodoHabilitado('pago_movil')) {
            if (!$this->pm_operadora) $errores[] = 'Operadora de pago móvil es requerida';
            if (!$this->pm_numero) $errores[] = 'Número de pago móvil es requerido';
        }

        return $errores;
    }

    /**
     * Scope para obtener solo datos de pago activos
     */
    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    /**
     * Scope para obtener solo datos de pago verificados
     */
    public function scopeVerificados($query)
    {
        return $query->where('verificado', true);
    }
}
