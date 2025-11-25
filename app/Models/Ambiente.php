<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ambiente extends Model
{
    protected $fillable = ['nombre', 'ubicacion'];
        public function productos()
    {
        return $this->belongsToMany(Producto::class, 'producto_ambiente', 'ambiente_id', 'producto_id')
            ->withPivot('cantidad')
            ->withTimestamps();
    }

}
