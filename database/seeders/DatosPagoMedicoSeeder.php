<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DatosPagoMedico;
use App\Models\Medico;

class DatosPagoMedicoSeeder extends Seeder
{
    public function run(): void
    {
        $medicos = Medico::all();
        
        foreach ($medicos as $medico) {
            // Crear datos de pago para cada médico con datos de ejemplo
            DatosPagoMedico::updateOrCreate(
                ['medico_id' => $medico->id],
                [
                    'banco' => 'Banco Nacional de Crédito',
                    'tipo_cuenta' => 'Ahorro',
                    'numero_cuenta' => '0134-0001-234567890',
                    'titular' => $medico->nombre_completo ?? 'Dr. ' . $medico->primer_nombre,
                    'cedula' => $medico->numero_documento ?? '12345678',
                    'metodo_pago_id' => 1,
                    'prefijo_tlf' => '+58',
                    'numero_tlf' => '4141234567'
                ]
            );
        }

        $this->command->info('Datos de pago de médicos creados exitosamente');
    }
}
