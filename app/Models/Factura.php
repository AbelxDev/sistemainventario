<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Factura extends Model
{
    protected $fillable = [
        'numero',
        'fecha',
        'proveedor_id',
        'estado',
        'pdf_ruta',
    ];

    protected $casts = [
        'fecha' => 'date',
    ];

    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class);
    }

    public function detalles()
    {
        return $this->hasMany(FacturaDetalle::class);
    }
}
