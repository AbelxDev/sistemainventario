<?php

// app/Models/TipoProducto.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoProducto extends Model
{
    protected $table = 'tipo_productos';

    protected $fillable = [
        'nombre',
        'prefijo',
        'descripcion',
    ];

    public function productos()
    {
        return $this->hasMany(Producto::class, 'tipo_id');
    }
}
