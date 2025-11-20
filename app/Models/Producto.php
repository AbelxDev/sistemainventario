<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Producto extends Model
{
    public function tipo(): BelongsTo
    {
        return $this->belongsTo(TipoProducto::class, 'tipo_id');
    }

    public function proveedorPrincipal(): BelongsTo
    {
        return $this->belongsTo(Proveedor::class, 'proveedor_principal_id');
    }

    public function proveedores(): BelongsToMany
    {
        return $this->belongsToMany(Proveedor::class, 'producto_proveedor');
    }
}
