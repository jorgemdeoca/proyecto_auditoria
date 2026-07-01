<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TasaDolar extends Model
{
    use HasFactory, \App\Traits\HasAuditTrail;

    protected string $auditModulo = 'configuracion';

    protected $table = 'tasas_dolar';
    protected $primaryKey = 'id';
    protected $fillable = [
        'fuente',
        'valor',
        'fecha_tasa',
        'status'
    ];

    public function facturasPacientes()
    {
        return $this->hasMany(FacturaPaciente::class, 'tasa_id');
    }

    public function pagos()
    {
        return $this->hasMany(Pago::class, 'tasa_aplicada_id');
    }

    public function facturasCabecera()
    {
        return $this->hasMany(FacturaCabecera::class, 'tasa_id');
    }
}
