<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConfiguracionGlobal extends Model
{
    use HasFactory;

    protected $table = 'configuracion_global';
    protected $fillable = [
        'clave',
        'valor',
        'descripcion',
        'tipo',
        'status'
    ];

    protected $casts = [
        'status' => 'boolean',
        'valor' => 'string'
    ];

    /**
     * Obtener valor de configuración por clave
     */
    public static function obtener($clave, $default = null)
    {
        $config = self::where('clave', $clave)
                    ->where('status', true)
                    ->first();
        
        if (!$config) {
            return $default;
        }

        return match($config->tipo) {
            'boolean' => filter_var($config->valor, FILTER_VALIDATE_BOOLEAN),
            'number' => is_numeric($config->valor) ? (float) $config->valor : $default,
            'json' => json_decode($config->valor, true),
            default => $config->valor
        };
    }

    /**
     * Establecer valor de configuración
     */
    public static function establecer($clave, $valor, $descripcion = null, $tipo = 'string')
    {
        $tipoDato = match(true) {
            is_bool($valor) => 'boolean',
            is_numeric($valor) => 'number',
            is_array($valor) => 'json',
            default => 'string'
        };

        return self::updateOrCreate(
            ['clave' => $clave],
            [
                'valor' => is_array($valor) ? json_encode($valor) : (string) $valor,
                'descripcion' => $descripcion,
                'tipo' => $tipoDato,
                'status' => true
            ]
        );
    }

    /**
     * Obtener configuración por defecto para reparto
     */
    public static function getRepartoPorDefecto()
    {
        return [
            'porcentaje_medico' => self::obtener('reparto_medico_default', 70),
            'porcentaje_consultorio' => self::obtener('reparto_consultorio_default', 20),
            'porcentaje_sistema' => self::obtener('reparto_sistema_default', 10)
        ];
    }

    /**
     * Validar que los porcentajes de reparto sumen 100
     */
    public static function validarReparto($medico = null, $consultorio = null, $sistema = null)
    {
        $total = ($medico ?? 0) + ($consultorio ?? 0) + ($sistema ?? 0);
        return abs($total - 100) < 0.01; // Permitir pequeñas diferencias de punto flotante
    }
}
