<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Proveedor extends Model
{

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

    public function productos(): BelongsToMany
    {
        return $this->belongsToMany(Producto::class, 'producto_proveedor');
    }
}
