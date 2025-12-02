<?php

namespace App\Livewire\FacturaDetalle;

use Livewire\Component;
use App\Models\Producto;

class Index extends Component
{

    public $producto_id;
    public $cantidad;
    public $recibidos;
    public $faltantes = 0;

    public function updated($field)
    {
        // Calcula faltantes automáticamente
        if ($this->cantidad && $this->recibidos !== null) {
            $this->faltantes = $this->cantidad - $this->recibidos;
        }
    }

    public function agregarDetalle()
    {
        // Aquí luego guardarás el detalle en DB
        // Por ahora solo mostramos datos
        dd([
            'producto_id' => $this->producto_id,
            'cantidad' => $this->cantidad,
            'recibidos' => $this->recibidos,
            'faltantes' => $this->faltantes,
        ]);
    }

    public function render()
    {
        return view('livewire.factura-detalle.index', [
            'productos' => Producto::all()
        ]);
    }
}
