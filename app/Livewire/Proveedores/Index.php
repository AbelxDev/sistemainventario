<?php

namespace App\Livewire\Proveedores;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Proveedor;

class Index extends Component
{
    use WithPagination;

    protected string $paginationTheme = 'bootstrap';

    public $modalVisible = false;
    public $modalEliminarVisible = false; // <--- NUEVO MODAL ELIMINAR
    public $nombreAEliminar; // para enviar en el modal de eliminación

    public $modo = 'crear';

    public $proveedor_id;
    public $razon_social;
    public $ruc;
    public $telefono;
    public $direccion;
    public $search = '';

    public $idAEliminar; // <--- ID para eliminar

    public function rules()
    {
        return [
            'razon_social' => 'required|string|max:255|unique:proveedors,razon_social,' . $this->proveedor_id,
            'ruc' => 'required|string|max:20|unique:proveedors,ruc,' . $this->proveedor_id,
            'telefono' => 'nullable|numeric',
            'direccion' => 'nullable|string|max:255',
        ];
    }
    protected $messages = [
        'razon_social.required' => 'La razón social es obligatoria.',
        'razon_social.max' => 'La razón social no puede exceder los :max caracteres.',
        
        'ruc.required' => 'El RUC es obligatorio.',
        'ruc.max' => 'El RUC no puede tener más de :max caracteres.',

        'razon_social.unique' => 'Esta razón social ya está registrada.',
        'ruc.unique' => 'Este RUC ya está registrada.',
    ];

    public function render()
    {
        return view('livewire.proveedores.index', [
            'proveedores' => Proveedor::where('razon_social', 'like', "%{$this->search}%")
                ->orWhere('ruc', 'like', "%{$this->search}%")
                ->orWhere('telefono', 'like', "%{$this->search}%")
                ->orWhere('direccion', 'like', "%{$this->search}%")
                ->orderBy('id', 'desc')
                ->paginate(10)
        ]);
    }
    // Abrir modal para crear
    public function crear()
    {
        $this->resetForForm();
        $this->modo = 'crear';
        $this->dispatch('open-modal');
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
        $this->dispatch('open-modal');
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

        $this->resetForForm();
        $this->dispatch('close-modal');
        $this->dispatch('success', message: 'Proveedor creado correctamente.');
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

        $this->dispatch('close-modal');
        $this->dispatch('success', message: 'Proveedor actualizado correctamente.');
    }

    public function confirmarEliminacion($id)
    {
        $proveedor = Proveedor::findOrFail($id);

        $this->idAEliminar = $id;
        $this->nombreAEliminar = $proveedor->razon_social;

        $this->modalEliminarVisible = true;

        $this->dispatch('open-delete-modal');
    }

    public function eliminar()
    {
        Proveedor::destroy($this->idAEliminar);
        $this->idAEliminar = null;
        $this->nombreAEliminar = null; // limpiamos
        $this->modalEliminarVisible = false;
        $this->dispatch('close-delete-modal');
        $this->dispatch('success', message: 'Proveedor eliminado correctamente.');
    }

    public function cerrarModalEliminar()
    {
        $this->modalEliminarVisible = false;
        $this->idAEliminar = null;
    }


    public function cerrarModal()
    {
         $this->dispatch('close-modal');
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

        $this->resetErrorBag();   // Limpia errores de validación
        $this->resetValidation(); // Limpia mensajes de validación
    }
}
