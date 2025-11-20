<?php

namespace App\Livewire\TiposProducto;

use Livewire\Component;
use App\Models\TipoProducto;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';

    public $tipo_id;
    public $nombre;
    public $prefijo;
    public $descripcion;

    public $modalMode = 'create'; // create | edit

    public $canEditPrefijo = true;


    protected function rules()
    {
        return [
            'nombre' => 'required|string|max:255',
            'prefijo' => 'required|string|max:10|unique:tipo_productos,prefijo,' . $this->tipo_id,
            'descripcion' => 'nullable|string|max:500',
        ];
    }

    public function resetFields()
    {
        $this->tipo_id = null;
        $this->nombre = '';
        $this->prefijo = '';
        $this->descripcion = '';
    }

    public function openCreateModal()
    {
        $this->modalMode = 'create';
        $this->resetFields();

        $this->dispatch('open-modal');
    }

    public function openEditModal($id)
    {
        $this->modalMode = 'edit';

        $tipo = TipoProducto::findOrFail($id);

        $this->tipo_id = $tipo->id;
        $this->nombre = $tipo->nombre;
        $this->prefijo = $tipo->prefijo;
        $this->descripcion = $tipo->descripcion;

        // ⚠️ NO permitir editar prefijo si ya tiene productos asociados
        $this->canEditPrefijo = $tipo->productos()->count() == 0;

        $this->dispatch('open-modal');
    }


    public function save()
    {
        $this->validate();

        TipoProducto::create([
            'nombre' => $this->nombre,
            'prefijo' => strtoupper($this->prefijo),
            'descripcion' => $this->descripcion,
        ]);

        session()->flash('success', 'Tipo de producto creado correctamente.');

        $this->resetFields();
        $this->dispatch('close-modal');
    }

    public function update()
    {
        $tipo = TipoProducto::findOrFail($this->tipo_id);

        // ❗ Validación de seguridad
        if ($tipo->productos()->count() > 0 && $this->prefijo !== $tipo->prefijo) {
            session()->flash('error', 'No se puede modificar el prefijo porque este tipo tiene productos asociados.');
            return;
        }

        $this->validate();

        $tipo->update([
            'nombre' => $this->nombre,
            'prefijo' => strtoupper($this->prefijo),
            'descripcion' => $this->descripcion,
        ]);

        session()->flash('success', 'Tipo de producto actualizado.');

        $this->dispatch('close-modal');
    }


    public function delete($id)
    {
        $tipo = TipoProducto::findOrFail($id);

        // Verificar si tiene productos asociados
        if ($tipo->productos()->count() > 0) {
            session()->flash('error', 'No se puede eliminar este tipo porque tiene productos asociados.');
            return;
        }

        $tipo->delete();

        session()->flash('success', 'Tipo de producto eliminado correctamente.');
    }


    public function render()
    {
        $tipos = TipoProducto::where('nombre', 'like', "%{$this->search}%")
            ->orWhere('prefijo', 'like', "%{$this->search}%")
            ->orderBy('id', 'DESC')
            ->paginate(20);

        return view('livewire.tipos-producto.index', compact('tipos'));
    }
}
