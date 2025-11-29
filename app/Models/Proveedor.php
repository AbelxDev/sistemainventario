<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Proveedor extends Model
{
    protected $table = 'proveedors';

    use HasFactory;

    protected $fillable = [
        'razon_social',
        'ruc',
        'telefono',
        'direccion',
    ];


    public function productosPrincipales(): HasMany
    {
        return $this->hasMany(Producto::class, 'proveedor_principal_id');
    }

    public function productos()
    {
        return $this->belongsToMany(
            Producto::class,
            'producto_proveedor',
            'proveedor_id',
            'producto_id'
        )->withTimestamps();
    }

    public function facturas()
    {
        return $this->hasMany(Factura::class);
    }


}
