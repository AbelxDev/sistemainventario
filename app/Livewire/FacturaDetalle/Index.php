<?php

namespace App\Livewire\FacturaDetalle;

use Livewire\Component;
use App\Models\Producto;

class Index extends Component
{
    public $producto_id = '';
    public $cantidad = 0;
    public $recibidos = 0;
    public $faltantes = 0;

    public $detalles = []; // Aquí se almacenan los detalles agregados

    // Recalcular faltantes cuando cambian cantidad o recibidos
    public function updated($field)
    {
        if (in_array($field, ['cantidad', 'recibidos'])) {
            $this->calcularFaltantes();
        }
    }

    public function calcularFaltantes()
    {
        $cant = (int) $this->cantidad;
        $rec = (int) $this->recibidos;

        if ($rec > $cant) {
            $rec = $cant;
            $this->recibidos = $cant;
        }

        $this->faltantes = $cant - $rec;
    }

    // Agrega el detalle a la tabla y limpia los campos
    public function agregarDetalle()
    {
        if (!$this->producto_id || $this->cantidad <= 0) {
            return; // luego puedes manejar validaciones
        }

        $this->detalles[] = [
            'producto_id' => $this->producto_id,
            'producto_nombre' => Producto::find($this->producto_id)->nombre,
            'cantidad' => $this->cantidad,
            'recibidos' => $this->recibidos,
            'faltantes' => $this->faltantes,
        ];

        // Limpiar formulario
        $this->producto_id = '';
        $this->cantidad = 0;
        $this->recibidos = 0;
        $this->faltantes = 0;
    }

    public function eliminarDetalle($index)
    {
        unset($this->detalles[$index]);
        $this->detalles = array_values($this->detalles); // reindexar
    }

    public function render()
    {
        return view('livewire.factura-detalle.index', [
            'productos' => Producto::all()
        ]);
    }
}
