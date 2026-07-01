<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            // Grupo 1: Tablas independientes
            RolesTableSeeder::class,
            EstadosTableSeeder::class,
            PreguntasCatalogoTableSeeder::class,
            EspecialidadesTableSeeder::class,
            MetodoPagoTableSeeder::class,
            ConfiguracionGlobalSeeder::class,
            
            // Grupo 2: Dependen de Grupo 1
            CiudadesTableSeeder::class,
            MunicipiosTableSeeder::class,
            UsuariosTableSeeder::class,
            
            // Grupo 3: Dependen de Grupo 2
            ParroquiasTableSeeder::class,
            AdministradoresTableSeeder::class,
            MedicosTableSeeder::class,
            PacientesTableSeeder::class,
            ConsultoriosTableSeeder::class,
            TasasDolarTableSeeder::class,
            
            // Grupo 4: Dependen de Grupo 3
            RepresentantesTableSeeder::class,
            PacientesEspecialesTableSeeder::class,
            MedicoEspecialidadTableSeeder::class,
            EspecialidadConsultorioTableSeeder::class,
            MedicoConsultorioTableSeeder::class,
            CitasTableSeeder::class,
            HistoriaClinicaBaseTableSeeder::class,
            DatosPagoMedicoSeeder::class,
            
            // Grupo 5: Dependen de Grupo 4
            RepresentantePacienteEspecialTableSeeder::class,
            EvolucionClinicaTableSeeder::class,
            OrdenesMedicasTableSeeder::class,
            FacturasPacientesTableSeeder::class,
            FacturaCabeceraTableSeeder::class,
            
            // Grupo 6: Dependen de Grupo 5
            PagoTableSeeder::class,
            FacturaDetallesTableSeeder::class,
            FacturaTotalesTableSeeder::class,
            ConfiguracionRepartoTableSeeder::class,
            LiquidacionesTableSeeder::class,
            LiquidacionDetallesTableSeeder::class,
            
            // Grupo 7: Sistemas complementarios
            NotificacionesTableSeeder::class,
            FechaIndisponibleTableSeeder::class,
            SolicitudesHistorialTableSeeder::class,
            RespuestasSeguridadTableSeeder::class,
            HistorialPasswordTableSeeder::class,
        ]);
    }
}
