<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvolucionClinica extends Model
{
    use HasFactory, \App\Traits\ScopedByConsultorio, \App\Traits\HasAuditTrail;

    protected string $auditModulo = 'historia_clinica';

    protected $table = 'evolucion_clinica';
    protected $primaryKey = 'id';
    protected $fillable = [
        'cita_id',
        'paciente_id',
        'medico_id',
        'peso_kg',
        'talla_cm',
        'imc',
        'tension_sistolica',
        'tension_diastolica',
        'frecuencia_cardiaca',
        'temperatura_c',
        'frecuencia_respiratoria',
        'saturacion_oxigeno',
        'motivo_consulta',
        'enfermedad_actual',
        'examen_fisico',
        'diagnostico',
        'tratamiento',
        'recomendaciones',
        'notas_adicionales',
        'status'
    ];

    public function cita()
    {
        return $this->belongsTo(Cita::class, 'cita_id');
    }

    public function paciente()
    {
        return $this->belongsTo(Paciente::class, 'paciente_id');
    }

    public function medico()
    {
        return $this->belongsTo(Medico::class, 'medico_id');
    }
}
