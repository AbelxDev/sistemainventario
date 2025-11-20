<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;



class Ambiente extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'ubicacion',
    ];

    public function productos()
    {
        return $this->belongsToMany(Producto::class, 'producto_ambiente', 'ambiente_id', 'producto_id')
            ->withPivot('cantidad')
            ->withTimestamps();
    }

}
