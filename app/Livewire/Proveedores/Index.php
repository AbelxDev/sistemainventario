<?php

namespace App\Livewire\Proveedores;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Proveedor;

class Index extends Component
{
    use WithPagination;

    public $modalVisible = false;
    public $modo = 'crear';

    public $proveedor_id;
    public $razon_social;
    public $ruc;
    public $telefono;
    public $direccion;

    protected $rules = [
        'razon_social' => 'required|string|max:255',
        'ruc' => 'required|string|max:11',
        'telefono' => 'nullable|string|max:20',
        'direccion' => 'nullable|string|max:255',
    ];

    public function render()
    {
        return view('livewire.proveedores.index', [
            'proveedores' => Proveedor::orderBy('id', 'desc')->paginate(10)
        ]);
    }

    // Abrir modal para crear
    public function crear()
    {
        $this->resetForForm();
        $this->modo = 'crear';
        $this->modalVisible = true;
    }

    // Abrir modal para editar
    public function editar($id)
    {
        $this->resetForForm();

        $proveedor = Proveedor::findOrFail($id);

        $this->proveedor_id = $proveedor->id;
        $this->razon_social = $proveedor->razon_social;
        $this->ruc = $proveedor->ruc;
        $this->telefono = $proveedor->telefono;
        $this->direccion = $proveedor->direccion;

        $this->modo = 'editar';
        $this->modalVisible = true;
    }

    public function guardar()
    {
        $this->validate();

        Proveedor::create([
            'razon_social' => $this->razon_social,
            'ruc' => $this->ruc,
            'telefono' => $this->telefono,
            'direccion' => $this->direccion,
        ]);

        $this->cerrarModal();
    }

    public function actualizar()
    {
        $this->validate();

        Proveedor::where('id', $this->proveedor_id)->update([
            'razon_social' => $this->razon_social,
            'ruc' => $this->ruc,
            'telefono' => $this->telefono,
            'direccion' => $this->direccion,
        ]);

        $this->cerrarModal();
    }

    public function confirmarEliminacion($id)
    {
        Proveedor::destroy($id);
    }

    public function cerrarModal()
    {
        $this->modalVisible = false;
    }

    public function resetForForm()
    {
        $this->reset([
            'proveedor_id',
            'razon_social',
            'ruc',
            'telefono',
            'direccion'
        ]);
    }
}
