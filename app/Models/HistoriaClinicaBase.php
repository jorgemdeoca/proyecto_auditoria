<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\EvolucionClinica;
use App\Models\AuditoriaHistoriaBase;

class HistoriaClinicaBase extends Model
{
    use HasFactory, \App\Traits\ScopedByConsultorio, \App\Traits\HasAuditTrail;

    protected $table = 'historia_clinica_base';
    protected $primaryKey = 'id';
    protected $fillable = [
        'paciente_id',
        'tipo_sangre',
        'alergias',
        'alergias_medicamentos',
        'antecedentes_familiares',
        'antecedentes_personales',
        'enfermedades_cronicas',
        'medicamentos_actuales',
        'cirugias_previas',
        'habitos',
        'status'
    ];

    public function paciente()
    {
        return $this->belongsTo(Paciente::class, 'paciente_id');
    }

    /**
     * Get the evoluciones clínicas for this patient through the paciente relationship
     */
    public function evoluciones()
    {
        return $this->hasMany(EvolucionClinica::class, 'paciente_id', 'paciente_id');
    }

    /**
     * Get the audit trail for this historia clínica base
     */
    public function auditorias()
    {
        return $this->hasMany(AuditoriaHistoriaBase::class, 'historia_clinica_base_id');
    }
}
