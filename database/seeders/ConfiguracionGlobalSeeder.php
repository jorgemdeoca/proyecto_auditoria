<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ConfiguracionGlobal;

class ConfiguracionGlobalSeeder extends Seeder
{
    public function run(): void
    {
        // Configuración por defecto para reparto de facturas
        ConfiguracionGlobal::updateOrCreate(
            ['clave' => 'reparto_medico_default'],
            [
                'valor' => '70',
                'descripcion' => 'Porcentaje por defecto para el médico en el reparto de facturas',
                'tipo' => 'number',
                'status' => true
            ]
        );

        ConfiguracionGlobal::updateOrCreate(
            ['clave' => 'reparto_consultorio_default'],
            [
                'valor' => '20',
                'descripcion' => 'Porcentaje por defecto para el consultorio en el reparto de facturas',
                'tipo' => 'number',
                'status' => true
            ]
        );

        ConfiguracionGlobal::updateOrCreate(
            ['clave' => 'reparto_sistema_default'],
            [
                'valor' => '10',
                'descripcion' => 'Porcentaje por defecto para el sistema en el reparto de facturas',
                'tipo' => 'number',
                'status' => true
            ]
        );

        // Configuración de liquidaciones
        ConfiguracionGlobal::updateOrCreate(
            ['clave' => 'liquidacion_tipo_periodo_default'],
            [
                'valor' => 'Quincenal',
                'descripcion' => 'Tipo de período por defecto para liquidaciones',
                'tipo' => 'string',
                'status' => true
            ]
        );

        ConfiguracionGlobal::updateOrCreate(
            ['clave' => 'liquidacion_generar_automatico'],
            [
                'valor' => 'false',
                'descripcion' => 'Generar liquidaciones automáticamente al finalizar el período',
                'tipo' => 'boolean',
                'status' => true
            ]
        );

        // Configuración de facturación
        ConfiguracionGlobal::updateOrCreate(
            ['clave' => 'factura_dias_vencimiento_default'],
            [
                'valor' => '7',
                'descripcion' => 'Días por defecto para vencimiento de facturas',
                'tipo' => 'number',
                'status' => true
            ]
        );

        ConfiguracionGlobal::updateOrCreate(
            ['clave' => 'factura_impuesto_iva'],
            [
                'valor' => '0',
                'descripcion' => 'Porcentaje de IVA aplicado a las facturas',
                'tipo' => 'number',
                'status' => true
            ]
        );

        // Configuración de métodos de pago
        ConfiguracionGlobal::updateOrCreate(
            ['clave' => 'pago_metodos_habilitados'],
            [
                'valor' => json_encode(['Transferencia', 'Zelle', 'Efectivo', 'Pago Movil']),
                'descripcion' => 'Métodos de pago habilitados en el sistema',
                'tipo' => 'json',
                'status' => true
            ]
        );

        $this->command->info('Configuración global por defecto creada exitosamente');
    }
}
