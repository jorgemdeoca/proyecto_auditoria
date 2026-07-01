<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FacturaPaciente extends Model
{
    use HasFactory, \App\Traits\ScopedByConsultorio, \App\Traits\HasAuditTrail;

    protected string $auditModulo = 'pagos';

    protected $table = 'facturas_pacientes';
    protected $primaryKey = 'id';
    protected $fillable = [
        'cita_id',
        'paciente_id',
        'medico_id',
        'monto_usd',
        'tasa_id',
        'monto_bs',
        'fecha_emision',
        'fecha_vencimiento',
        'numero_factura',
        'status_factura',
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

    public function tasa()
    {
        return $this->belongsTo(TasaDolar::class, 'tasa_id');
    }

    public function pagos()
    {
        return $this->hasMany(Pago::class, 'id_factura_paciente');
    }
}
