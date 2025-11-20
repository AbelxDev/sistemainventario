<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductoAmbiente extends Model
{
    protected $table = 'producto_ambiente';

    protected $fillable = [
        'producto_id',
        'ambiente_id',
        'cantidad',
    ];

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }

    public function ambiente()
    {
        return $this->belongsTo(Ambiente::class);
    }
}
