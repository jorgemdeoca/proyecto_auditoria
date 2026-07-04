<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Paciente extends Model
{
    use HasFactory, Notifiable, \App\Traits\HasAuditTrail;

    protected string $auditModulo = 'pacientes';

    protected $table = 'pacientes';
    protected $primaryKey = 'id';
    protected $fillable = [
        'user_id',
        'primer_nombre',
        'segundo_nombre',
        'primer_apellido',
        'segundo_apellido',
        'tipo_documento',
        'numero_documento',
        'fecha_nac',
        'estado_id',
        'ciudad_id',
        'municipio_id',
        'parroquia_id',
        'direccion_detallada',
        'prefijo_tlf',
        'numero_tlf',
        'genero',
        'ocupacion',
        'estado_civil',
        'foto_perfil',
        'banner_perfil',
        'banner_color',
        'tema_dinamico',
        'es_especial',
        'status'
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'user_id');
    }

    public function estado()
    {
        return $this->belongsTo(Estado::class, 'estado_id');
    }

    public function ciudad()
    {
        return $this->belongsTo(Ciudad::class, 'ciudad_id');
    }

    public function municipio()
    {
        return $this->belongsTo(Municipio::class, 'municipio_id');
    }

    public function parroquia()
    {
        return $this->belongsTo(Parroquia::class, 'parroquia_id');
    }

    public function pacientesEspeciales()
    {
        return $this->hasMany(PacienteEspecial::class, 'paciente_id');
    }

    // Relación para cuando ESTE paciente es un paciente especial
    public function pacienteEspecial()
    {
        return $this->hasOne(PacienteEspecial::class, 'paciente_id');
    }

    public function citas()
    {
        return $this->hasMany(Cita::class, 'paciente_id');
    }

    public function historiaClinicaBase()
    {
        return $this->hasOne(HistoriaClinicaBase::class, 'paciente_id');
    }

    public function evolucionesClinicas()
    {
        return $this->hasMany(EvolucionClinica::class, 'paciente_id');
    }

    public function ordenesMedicas()
    {
        return $this->hasMany(OrdenMedica::class, 'paciente_id');
    }

    public function facturasPacientes()
    {
        return $this->hasMany(FacturaPaciente::class, 'paciente_id');
    }

    public function facturasCabecera()
    {
        return $this->hasMany(FacturaCabecera::class, 'paciente_id');
    }

    public function solicitudesHistorial()
    {
        return $this->hasMany(SolicitudHistorial::class, 'paciente_id');
    }

    /**
     * Relación: cuando este paciente actúa como representante
     */
    public function representante()
    {
        return $this->hasOne(Representante::class, 'paciente_id');
    }

    /**
     * Verificar si es paciente especial
     */
    public function getEsEspecialAttribute($value)
    {
        return $value == 1;
    }

    /**
     * Route notifications for the mail channel.
     *
     * @return  array<string, string>|string
     */
    public function routeNotificationForMail($notification)
    {
        return $this->usuario->correo ?? null;
    }

    public function getNombreCompletoAttribute()
    {
        return $this->primer_nombre . ' ' . $this->primer_apellido;
    }

    /**
     * Get the channels the entity receives broadcast notifications on.
     */
    public function receivesBroadcastNotificationsOn(): string
    {
        return 'App.Models.Paciente.' . $this->id;
    }
}
