<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    use HasFactory, \App\Traits\ScopedByConsultorio, \App\Traits\HasAuditTrail;

    protected $table = 'pago';
    protected $primaryKey = 'id_pago';
    protected $fillable = [
        'id_factura_paciente',
        'id_metodo',
        'fecha_pago',
        'monto_pagado_bs',
        'monto_equivalente_usd',
        'tasa_aplicada_id',
        'referencia',
        'comentarios',
        'comprobante',
        'estado',
        'confirmado_por',
        'status'
    ];

    public function facturaPaciente()
    {
        return $this->belongsTo(FacturaPaciente::class, 'id_factura_paciente');
    }

    public function metodoPago()
    {
        return $this->belongsTo(MetodoPago::class, 'id_metodo');
    }

    public function tasaAplicada()
    {
        return $this->belongsTo(TasaDolar::class, 'tasa_aplicada_id');
    }

    public function confirmadoPor()
    {
        return $this->belongsTo(Administrador::class, 'confirmado_por');
    }
}
