<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Ambiente;

class Ambientes extends Component
{
    use WithPagination;

    protected string $paginationTheme = 'bootstrap';

    public $search = "";

    public $modo = 'crear';

    public $ambiente_id;
    public $nombre;
    public $ubicacion;

    public $confirmarEliminacion = false;

    protected $messages = [
        'nombre.required' => 'El nombre es obligatorio.',
        'ubicacion.required' => 'La ubicación es obligatoria.',
        'nombre.min' => 'Debe tener al menos 3 caracteres.',
        'ubicacion.min' => 'Debe tener al menos 3 caracteres.',
    ];

    protected $rules = [
        'nombre' => 'required|string|min:3',
        'ubicacion' => 'required|string|min:3',
    ];


    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function crear()
    {
        $this->reset(['ambiente_id', 'nombre', 'ubicacion']);
        $this->modo = 'crear';

        $this->resetErrorBag();
        $this->dispatch('abrirModalForm');
    }

    public function editar($id)
    {
        $a = Ambiente::findOrFail($id);

        $this->ambiente_id = $a->id;
        $this->nombre = $a->nombre;
        $this->ubicacion = $a->ubicacion;

        $this->modo = 'editar';

        $this->resetErrorBag();
        $this->dispatch('abrirModalForm');
        
    }

    public function guardar()
    {
        $this->validate();

        if ($this->modo === 'crear') {
            Ambiente::create([
                'nombre' => $this->nombre,
                'ubicacion' => $this->ubicacion,
            ]);
            
            //mensaje para el guardado
            $this->dispatch('success', message: 'Ambiente creado correctamente!');
        
        } else {
            Ambiente::findOrFail($this->ambiente_id)->update([
                'nombre' => $this->nombre,
                'ubicacion' => $this->ubicacion,
            ]);
            
            //Mensaje para cuando se edite.
            $this->dispatch('success', message: 'Ambiente actualizado correctamente!');

        }



        $this->dispatch('cerrarModalForm');
    }

    public function cerrarModal()
    {
        $this->dispatch('cerrarModalForm');
    }

    public function confirmarEliminar($id)
    {
        $this->ambiente_id = $id;
        $this->dispatch('abrirModalEliminar');
    }

    public function cancelarEliminar()
    {
        $this->dispatch('cerrarModalEliminar');
    }

    public function eliminarDefinitivo()
    {
        Ambiente::findOrFail($this->ambiente_id)->delete();
        $this->dispatch('cerrarModalEliminar');
         $this->dispatch('success', message: 'Ambiente eliminado correctamente!');
    }

    public function render()
    {
        $ambientes = Ambiente::where('nombre', 'like', '%' . $this->search . '%')
            ->orWhere('ubicacion', 'like', '%' . $this->search . '%')
            ->orderBy('id', 'desc')
            //cambiar a 20.
            ->paginate(20);

        return view('livewire.ambientes', [
            'ambientes' => $ambientes
        ]);
    }
}
