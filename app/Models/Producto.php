<?php

// app/Models/Producto.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $fillable = [
        'codigo',
        'nombre',
        'descripcion',
        'tipo_id',
        'proveedor_principal_id',
    ];

    public function tipo()
    {
        return $this->belongsTo(TipoProducto::class, 'tipo_id');
    }

    public function proveedorPrincipal()
    {
        return $this->belongsTo(Proveedor::class, 'proveedor_principal_id');
    }

    public function proveedores()
    {
        return $this->belongsToMany(
            Proveedor::class,
            'producto_proveedor',
            'producto_id',
            'proveedor_id'
        )->withTimestamps();
    }

    public function ambientes()
    {
        return $this->belongsToMany(Ambiente::class, 'producto_ambiente', 'producto_id', 'ambiente_id')
            ->withPivot('cantidad')
            ->withTimestamps();
    }

}
