<?php

namespace App\Livewire\TiposProducto;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\TipoProducto;
use Illuminate\Validation\Rule;

class Index extends Component
{
    use WithPagination;

    protected string $paginationTheme = 'bootstrap';

    public $search = '';

    public $tipo_id;
    public $nombre;
    public $prefijo;
    public $descripcion;

    public $modalMode = 'create'; // create | edit
    public $canEditPrefijo = true;

    public $tipo_id_a_eliminar;

    protected $messages = [
        'nombre.required' => 'El nombre es obligatorio.',
        'prefijo.required' => 'El prefijo es obligatorio.',
        'prefijo.unique'   => 'El prefijo ya está en uso.',
        'prefijo.max' => 'El campo de prefijo no debe tener más de 10 caracteres.'
    ];

    protected function rules()
    {
        return [
            'nombre' => 'required|string|max:255',
            'prefijo' => [
                'required',
                'string',
                'max:10',
                Rule::unique('tipo_productos', 'prefijo')->ignore($this->tipo_id),
            ],
            'descripcion' => 'nullable|string|max:500',
        ];
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    // ================= MODALES =================
    public function openCreateModal()
    {
        $this->resetFields();
        $this->modalMode = 'create';

        $this->resetErrorBag();
        $this->dispatch('open-modal');
    }

    public function openEditModal($id)
    {
        $tipo = TipoProducto::findOrFail($id);

        $this->tipo_id = $tipo->id;
        $this->nombre = $tipo->nombre;
        $this->prefijo = $tipo->prefijo;
        $this->descripcion = $tipo->descripcion;

        $this->canEditPrefijo = $tipo->productos()->count() === 0;
        $this->modalMode = 'edit';

        $this->resetErrorBag();
        $this->dispatch('open-modal');
    }

    public function resetFields()
    {
        $this->tipo_id = null;
        $this->nombre = '';
        $this->prefijo = '';
        $this->descripcion = '';
        $this->canEditPrefijo = true;
    }

    // ================= GUARDAR / ACTUALIZAR =================
    public function save()
    {
        $this->validate();

        TipoProducto::create([
            'nombre' => $this->nombre,
            'prefijo' => strtoupper($this->prefijo),
            'descripcion' => $this->descripcion,
        ]);

        $this->dispatch('success', message: 'Tipo de producto creado correctamente.');
        $this->resetFields();
        $this->dispatch('close-modal');
    }

    public function update()
    {
        $tipo = TipoProducto::findOrFail($this->tipo_id);

        if ($tipo->productos()->count() > 0 && $this->prefijo !== $tipo->prefijo) {
            $this->dispatch('error', message: 'No se puede modificar el prefijo porque este tipo tiene productos asociados.');
            return;
        }

        $this->validate();

        $tipo->update([
            'nombre' => $this->nombre,
            'prefijo' => strtoupper($this->prefijo),
            'descripcion' => $this->descripcion,
        ]);

        $this->dispatch('success', message: 'Tipo de producto actualizado.');
        $this->resetFields();
        $this->dispatch('close-modal');
    }

    // ================= ELIMINAR =================
    public function confirmarEliminar($id)
    {
        $this->tipo_id_a_eliminar = $id;
        $this->dispatch('abrirModalEliminar');
    }

    public function eliminarDefinitivo()
    {
        $tipo = TipoProducto::findOrFail($this->tipo_id_a_eliminar);

        if ($tipo->productos()->count() > 0) {
            $this->dispatch('error', message: 'No se puede eliminar este tipo porque tiene productos asociados.');
            $this->dispatch('cerrarModalEliminar');
            return;
        }

        $tipo->delete();
        $this->dispatch('success', message: 'Tipo de producto eliminado correctamente.');
        $this->dispatch('cerrarModalEliminar');
    }

    public function cancelarEliminar()
    {
        $this->dispatch('cerrarModalEliminar');
    }

    // ================= RENDER =================
    public function render()
    {
        $tipos = TipoProducto::where('nombre', 'like', "%{$this->search}%")
            ->orWhere('prefijo', 'like', "%{$this->search}%")
            ->orderBy('id', 'DESC')
            ->paginate(20);

        return view('livewire.tipos-producto.index', [
            'tipos' => $tipos
        ]);
    }
}
