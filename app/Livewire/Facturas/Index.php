<?php

namespace App\Livewire\Facturas;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\Factura;
use App\Models\Proveedor;
use Illuminate\Support\Facades\Storage;

class Index extends Component
{
    use WithPagination, WithFileUploads;

    protected string $paginationTheme = 'bootstrap';

    public $search = '';
    public $modalMode = 'create';

    public $factura_id;
    public $numero;
    public $fecha;
    public $proveedor_id;
    public $estado = 'pendiente';

    public $pdf_file;   // archivo temporal
    public $pdf_ruta;   // ruta final guardada
    public $pdf_preview_path;


    public $proveedores = [];

    public $confirmarEliminacion = false;

    protected $messages = [
        'numero.required' => 'El número es obligatorio.',
        'numero.unique'   => 'Este número ya existe.',
        'fecha.required'  => 'La fecha es obligatoria.',
        'proveedor_id.required' => 'Debe seleccionar un proveedor.',
        'estado.required' => 'El estado es obligatorio.',
        'pdf_file.mimes'  => 'El archivo debe ser un PDF.',
        'pdf_file.max'    => 'El archivo PDF no puede superar los 5MB.',
    ];

    protected function rules()
    {
        return [
            'numero' => 'required|string|max:50|unique:facturas,numero,' . $this->factura_id,
            'fecha' => 'required|date',
            'proveedor_id' => 'required|exists:proveedors,id',
            'estado' => 'required|in:pendiente,procesada,anulada',
            'pdf_file' => 'nullable|mimes:pdf|max:5120'
        ];
    }


    public function mount()
    {
        $this->proveedores = Proveedor::orderBy('razon_social')->get();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }



    private function resetForm()
    {
        $this->reset([
            'factura_id',
            'numero',
            'fecha',
            'proveedor_id',
            'estado',
            'pdf_file',
            'pdf_ruta',
            'pdf_preview_path',
        ]);

        $this->estado = 'pendiente';
    }



    // ================================
    //           CREAR
    // ================================
    public function openCreateModal()
    {
        $this->modalMode = 'create';
        $this->resetForm();

        $this->dispatch('abrirModalForm');
    }


    public function save()
    {
        $this->validate();

        // mover PDF del temporal a carpeta final
        $path = null;

        if ($this->pdf_file) {
            $path = $this->pdf_file->store('facturas', 'public');
        }

        // eliminar archivo temporal de la vista previa
        if ($this->pdf_preview_path) {
            Storage::disk('public')->delete($this->pdf_preview_path);
            $this->pdf_preview_path = null;
        }

        Factura::create([
            'numero' => $this->numero,
            'fecha' => $this->fecha,
            'proveedor_id' => $this->proveedor_id,
            'estado' => $this->estado,
            'pdf_ruta' => $path
        ]);

        $this->dispatch('cerrarModalForm');
        $this->dispatch('success', message: 'Factura creada correctamente!');
    }




    // ================================
    //           EDITAR
    // ================================
    public function openEditModal($id)
    {
        $this->modalMode = 'edit';

        $f = Factura::findOrFail($id);

        $this->factura_id = $f->id;
        $this->numero = $f->numero;
        $this->fecha = $f->fecha?->format('Y-m-d');
        $this->proveedor_id = $f->proveedor_id;
        $this->estado = $f->estado;
        $this->pdf_ruta = $f->pdf_ruta;

        // limpiar vista previa
        $this->pdf_preview_path = null;
        $this->pdf_file = null;

        $this->dispatch('abrirModalForm');
    }



    public function update()
    {
        $this->validate();

        $factura = Factura::findOrFail($this->factura_id);

        if ($this->pdf_file) {

            // borrar pdf anterior
            if ($factura->pdf_ruta && Storage::disk('public')->exists($factura->pdf_ruta)) {
                Storage::disk('public')->delete($factura->pdf_ruta);
            }

            // guardar nuevo pdf
            $factura->pdf_ruta = $this->pdf_file->store('facturas', 'public');
        }

        // eliminar temporal si existía
        if ($this->pdf_preview_path) {
            Storage::disk('public')->delete($this->pdf_preview_path);
            $this->pdf_preview_path = null;
        }

        $factura->update([
            'numero' => $this->numero,
            'fecha' => $this->fecha,
            'proveedor_id' => $this->proveedor_id,
            'estado' => $this->estado
        ]);

        $this->dispatch('cerrarModalForm');
        $this->dispatch('success', message: 'Factura actualizada correctamente!');
    }




    // ================================
    //           ELIMINAR
    // ================================
    public function confirmarEliminar($id)
    {
        $this->factura_id = $id;
        $this->dispatch('abrirModalEliminar');
    }

    public function cancelarEliminar()
    {
        $this->dispatch('cerrarModalEliminar');
    }


    public function eliminarDefinitivo()
    {
        $f = Factura::findOrFail($this->factura_id);

        if ($f->pdf_ruta && Storage::disk('public')->exists($f->pdf_ruta)) {
            Storage::disk('public')->delete($f->pdf_ruta);
        }

        $f->delete();

        $this->dispatch('cerrarModalEliminar');
        $this->dispatch('success', message: 'Factura eliminada correctamente!');
    }



    // ================================
    //           RENDER
    // ================================
    public function render()
    {
        $facturas = Factura::with('proveedor')
            ->where(function ($q) {
                $q->where('numero', 'like', "%{$this->search}%")
                  ->orWhereHas('proveedor', function ($q2) {
                      $q2->where('razon_social', 'like', "%{$this->search}%");
                  });
            })
            ->orderBy('fecha', 'desc')
            ->paginate(20);

        return view('livewire.facturas.index', compact('facturas'));
    }


    public function updatedPdfFile()
    {
        if ($this->pdf_file) {
            $this->pdf_preview_path = $this->pdf_file->store('tmp', 'public');
        }
    }

    public $resetKey = 0;

    public function resetModalFactura()
    {
        $this->reset(['pdf_file', 'pdf_preview_path']);
        $this->resetKey++; // fuerza a regenerar el input file
    }

    protected $listeners = [
        'resetModalFactura' => 'resetModalFactura'
    ];



}
