<?php

namespace App\Livewire\FacturaDetalle;

use Livewire\Component;
use App\Models\Producto;

class Index extends Component
{

    public $producto_id;
    public $cantidad = 0;
    public $recibidos = 0;
    public $faltantes = 0;

    public function updated($field)
    {
        if ($field === 'cantidad' || $field === 'recibidos') {
            $this->calcularFaltantes();
        }
    }

    
    public function calcularFaltantes()
    {
        $cant = (int)$this->cantidad;
        $rec = (int)$this->recibidos;

        if ($rec > $cant) {
            $rec = $cant;
            $this->recibidos = $cant;
        }

        $this->faltantes = $cant - $rec;
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
