<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cita extends Model
{
    use HasFactory, \App\Traits\ScopedByConsultorio, \App\Traits\HasAuditTrail;

    protected string $auditModulo = 'citas';

    protected $table = 'citas';
    protected $primaryKey = 'id';
    protected $fillable = [
        'paciente_id',
        'paciente_especial_id',
        'representante_id',
        'medico_id',
        'especialidad_id',
        'consultorio_id',
        'fecha_cita',
        'hora_inicio',
        'hora_fin',
        'duracion_minutos',
        'tarifa',
        'tarifa_extra',
        'tipo_consulta',
        'direccion_domicilio',
        'estado_cita',
        'motivo',
        'observaciones',
        'status'
    ];

    public function paciente()
    {
        return $this->belongsTo(Paciente::class, 'paciente_id');
    }

    public function pacienteEspecial()
    {
        return $this->belongsTo(PacienteEspecial::class, 'paciente_especial_id');
    }

    public function representante()
    {
        return $this->belongsTo(Representante::class, 'representante_id');
    }

    public function medico()
    {
        return $this->belongsTo(Medico::class, 'medico_id');
    }

    public function especialidad()
    {
        return $this->belongsTo(Especialidad::class, 'especialidad_id');
    }

    public function consultorio()
    {
        return $this->belongsTo(Consultorio::class, 'consultorio_id');
    }

    public function evolucionClinica()
    {
        return $this->hasOne(EvolucionClinica::class, 'cita_id');
    }

    public function ordenesMedicas()
    {
        return $this->hasMany(OrdenMedica::class, 'cita_id');
    }

    public function facturaPaciente()
    {
        return $this->hasOne(FacturaPaciente::class, 'cita_id');
    }

    public function facturaCabecera()
    {
        return $this->hasOne(FacturaCabecera::class, 'cita_id');
    }

    public function solicitudesHistorial()
    {
        return $this->hasMany(SolicitudHistorial::class, 'cita_id');
    }
    
    // Calcular tarifa total
    public function getTarifaTotalAttribute()
    {
        return ($this->tarifa ?? 0) + ($this->tarifa_extra ?? 0);
    }
}

