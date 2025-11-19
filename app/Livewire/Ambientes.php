<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Ambiente;

class Ambientes extends Component
{
    public $search = '';
    public $modal = false;
    public $modo = 'crear';

    public $ambiente_id;
    public $nombre;
    public $ubicacion;

    public $idAEliminar = null;
    public $confirmarEliminacion = false;

    // VALIDACIÓN
    protected $rules = [
        'nombre' => 'required|min:3',
        'ubicacion' => 'required|min:3',
    ];

    protected $messages = [
        'nombre.required' => 'El nombre del ambiente es obligatorio.',
        'nombre.min' => 'El nombre debe tener al menos 3 caracteres.',

        'ubicacion.required' => 'La ubicación es obligatoria.',
        'ubicacion.min' => 'La ubicación debe tener al menos 3 caracteres.',
    ];

    public function render()
    {
        $ambientes = Ambiente::where('nombre', 'like', '%' . $this->search . '%')
            ->orWhere('ubicacion', 'like', '%' . $this->search . '%')
            ->orderBy('id', 'desc')
            ->get();

        return view('livewire.ambientes', compact('ambientes'));
    }

    // Abrir modal para crear
    public function crear()
    {
        $this->resetCampos();
        $this->modo = 'crear';
        $this->modal = true;
    }

    // Abrir modal para editar
    public function editar($id)
    {
        $amb = Ambiente::findOrFail($id);

        $this->ambiente_id = $amb->id;
        $this->nombre = $amb->nombre;
        $this->ubicacion = $amb->ubicacion;

        $this->modo = 'editar';
        $this->modal = true;
    }

    // Guardar cambios (crear o editar)
    public function guardar()
    {
        $this->validate(); // Usa las reglas definidas arriba

        if ($this->modo === "crear") {

            Ambiente::create([
                'nombre' => $this->nombre,
                'ubicacion' => $this->ubicacion
            ]);

        } else {

            Ambiente::where('id', $this->ambiente_id)->update([
                'nombre' => $this->nombre,
                'ubicacion' => $this->ubicacion
            ]);
        }

        $this->cerrarModal();
        session()->flash('mensaje', 'El ambiente se guardó correctamente.');
    }

    // Eliminar directo (si lo sigues usando)
    public function eliminar($id)
    {
        Ambiente::destroy($id);
    }

    // Confirmación de eliminación
    public function confirmarEliminar($id)
    {
        $this->idAEliminar = $id;
        $this->confirmarEliminacion = true;
    }

    public function cancelarEliminar()
    {
        $this->confirmarEliminacion = false;
        $this->idAEliminar = null;
    }

    public function eliminarDefinitivo()
    {
        Ambiente::find($this->idAEliminar)->delete();

        $this->confirmarEliminacion = false;
        $this->idAEliminar = null;

        session()->flash('mensaje', 'Ambiente eliminado correctamente.');
    }

    // Cerrar modal
    public function cerrarModal()
    {
        $this->modal = false;
    }

    // Limpiar campos
    public function resetCampos()
    {
        $this->ambiente_id = null;
        $this->nombre = '';
        $this->ubicacion = '';
    }
}
